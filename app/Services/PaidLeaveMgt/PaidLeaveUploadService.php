<?php

namespace App\Services\PaidLeaveMgt;

use App\Models\PaidLeave;
use Carbon\CarbonImmutable;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class PaidLeaveUploadService
{
    public function importData($file)
    {
        // ストレージに保存する際のファイル名を設定
        $name = 'paid_leave_upload_data.csv';
        // 保存先のストレージ階層を取得
        $spath = storage_path('app/');
        // ストレージにデータを保存
        $stored = $file->storeAs('public/import', $name);
        // 保存に失敗した場合（既存ファイルの権限不足など）は処理を中断
        if ($stored === false) {
            throw new \Exception('アップロードファイルの保存に失敗しました。（storage/app/public/import の権限を確認してください）');
        }
        $path = $spath.$stored;
        // Excelで保存したCSV(Shift_JIS)にも対応するため、UTF-8に変換して保存し直す
        $contents = file_get_contents($path);
        if (!mb_check_encoding($contents, 'UTF-8')) {
            file_put_contents($path, mb_convert_encoding($contents, 'UTF-8', 'SJIS-win'));
        }
        return $path;
    }

    public function checkHeader($path)
    {
        // CSVファイルをオープンする
        $handle = fopen($path, 'r');
        // BOMを削除する
        rewind($handle);
        $bom = fread($handle, 3);
        if ($bom !== pack('CCC', 0xef, 0xbb, 0xbf)) {
            rewind($handle);
        }
        // ヘッダー行を取得する
        $import_data_header = fgetcsv($handle);
        // ファイルを閉じる
        fclose($handle);
        // ヘッダーが取得できない場合
        if ($import_data_header === false) {
            return 'ヘッダーが正しくない為、アップロードできませんでした。';
        }
        // 取り込まれるべきCSVのヘッダーを配列にセット
        $header = PaidLeave::csvHeader();
        // ヘッダーを比較し、差分があればエラー情報を返す
        if (!empty(array_diff_assoc($header, $import_data_header)) || count($import_data_header) != count($header)) {
            return 'ヘッダーが正しくない為、アップロードできませんでした。（必要なヘッダー：'.implode('、', $header).'）';
        }
        return null;
    }

    public function setArrayImportData($path)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($path);
        // アップロード処理に使用する配列をセット
        $upload_data = [];
        $validation_error = [];
        // ファイル内の重複チェック用
        $keys = [];
        // 取得したレコードの分だけループ
        foreach ($all_line as $key => $line) {
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            // 空行はスキップ
            if (count(array_filter($line, fn($value) => trim((string)$value) !== '')) == 0) {
                continue;
            }
            // 追加先テーブルのカラム名に合わせて配列を整理
            $param = [
                'employee_no' => trim((string)$line['従業員番号']),
                'paid_leave_date' => trim((string)$line['有給取得日']),
                'paid_leave_days' => trim((string)$line['取得日数']),
            ];
            // インポートデータのバリデーション処理
            $message = $this->validationImportData($param, $key + 2);
            // エラーメッセージがあればバリデーションエラーを配列に格納
            if (!is_null($message)) {
                $validation_error[] = $message;
                continue;
            }
            // 日付・日数を整形
            $param['paid_leave_date'] = CarbonImmutable::parse($param['paid_leave_date'])->toDateString();
            $param['paid_leave_days'] = (float)$param['paid_leave_days'];
            // ファイル内で同じ従業員・同じ日が重複していないか確認
            $unique_key = $param['employee_no'].'_'.$param['paid_leave_date'];
            if (isset($keys[$unique_key])) {
                $validation_error[] = ($key + 2).'行目：'.$keys[$unique_key].'行目と従業員番号・有給取得日が重複しています。';
                continue;
            }
            $keys[$unique_key] = $key + 2;
            // 追加用の配列に整理した情報を格納
            $upload_data[] = $param;
        }
        return compact('upload_data', 'validation_error');
    }

    public function validationImportData($param, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'employee_no' => 'required|exists:employees,employee_no',
            'paid_leave_date' => 'required|date',
            'paid_leave_days' => 'required|numeric|in:0.5,1,1.0',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required' => ":attributeは必須です。",
            'exists' => ":attributeが存在しません。",
            'date' => ":attributeが日付ではありません。",
            'numeric' => ":attributeは数値で入力して下さい。",
            'in' => ":attributeは0.5または1で入力して下さい。",
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'employee_no' => '従業員番号',
            'paid_leave_date' => '有給取得日',
            'paid_leave_days' => '取得日数',
        ];
        // バリデーション実施
        $validator = Validator::make($param, $rules, $messages, $attributes);
        // エラーがなければnullを返す
        if (!$validator->fails()) {
            return null;
        }
        // 項目ごとに最初のエラーメッセージを取得し、連結
        $message = collect($validator->errors()->toArray())->map(fn($errors) => $errors[0])->implode(' / ');
        return $record_num.'行目：'.$message;
    }

    public function makeValidationErrorMessage($validation_error)
    {
        // 表示件数の上限
        $limit = 5;
        $message = 'データが正しくない為、アップロードできませんでした。'.'<br>'.implode('<br>', array_slice($validation_error, 0, $limit));
        // 上限を超える場合は残り件数を表示
        if (count($validation_error) > $limit) {
            $message .= '<br>'.'他'.(count($validation_error) - $limit).'件';
        }
        return $message;
    }

    // 有給更新処理(従業員番号＋有給取得日が同じものは上書き)
    public function upsertPaidLeave($upload_data)
    {
        // データがある場合のみupsert
        if (empty($upload_data)) {
            return 0;
        }
        // タイムスタンプをセット
        $now = CarbonImmutable::now();
        $upload_data = array_map(fn($param) => $param + ['created_at' => $now, 'updated_at' => $now], $upload_data);
        // 件数が多い場合を考慮して分割して処理
        foreach (array_chunk($upload_data, 500) as $chunk) {
            PaidLeave::upsert($chunk, ['employee_no', 'paid_leave_date'], ['paid_leave_days', 'updated_at']);
        }
        return count($upload_data);
    }
}
