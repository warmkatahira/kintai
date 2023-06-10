<?php

namespace App\Services\HolidayMgt;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Holiday;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;

class HolidayUploadService
{
    public function importData($file)
    {
        // 選択したデータのファイル名を取得
        $uploaded_file = $file->getClientOriginalName();
        // ストレージに保存する際のファイル名を設定
        $name = 'holiday_upload_data.csv';
        // 保存先のストレージ階層を取得
        $spath = storage_path('app/');
        // ストレージにデータを保存
        $path = $spath.$file->storeAs('public/import', $name);
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
        // 取り込まれるべきCSVのヘッダーを配列にセット
        $header = Holiday::csvHeader();
        // ヘッダーを比較し、差分があればエラー情報を返す
        if (!empty(array_diff_assoc($import_data_header, $header))) {
            return 'ヘッダーが正しくない為、アップロードできませんでした。';
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
        // データがある場合のみ処理する
        if($all_line->isNotEmpty()){
            // バリデーションエラー出力ファイルのヘッダーを定義
            $validation_error_export_header = array('エラー行数', 'エラー内容');
            // 取得したレコードの分だけループ
            foreach ($all_line as $key => $line) {
                // UTF-8形式に変換した1行分のデータを取得
                $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
                // 追加先テーブルのカラム名に合わせて配列を整理
                $param = [
                    'holiday' => $line['休日'],
                    'holiday_note' => strlen($line['備考'] > 0) ? $line['備考'] : null,
                    'is_national_holiday' => $line['国民の祝日'],
                ];
                // インポートデータのバリデーション処理
                $message = $this->validationImportData($param, $key + 2);
                // エラーメッセージがあればバリデーションエラーを配列に格納
                if (!is_null($message)) {
                    $validation_error[] = array_combine($validation_error_export_header, $message);
                }
                // 追加用の配列に整理した情報を格納
                $upload_data[] = $param;
            }
        }
        return compact('upload_data', 'validation_error');
    }

    public function validationImportData($param, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'holiday' => 'required|date',
            'holiday_note' => 'nullable|max:20',
            'is_national_holiday' => 'required|boolean',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required' => ":attributeは必須です。",
            'max' => ":attributeは:max文字以内で入力して下さい。",
            'date' => ":attributeが日付ではありません。",
            'boolean' => ":attributeが正しくありません。",
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'holiday' => '休日',
            'holiday_note' => '備考',
            'is_national_holiday' => '国民の祝日',
        ];
        // バリデーション実施
        $validator = Validator::make($param, $rules, $messages, $attributes);
        // バリデーションエラーメッセージを格納する変数をセット
        $message = '';
        // バリデーションエラーの分だけループ
        foreach($validator->errors()->toArray() as $errors){
            // メッセージを格納
            $message = empty($message) ? array_shift($errors) : $message . ' / ' . array_shift($errors);
        }
        return empty($message) ? null : array($record_num.'行目', $message);
    }

    // 休日マスタ更新処理
    public function updateHoliday($upload_data)
    {
        // テーブルをロック
        Holiday::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        Holiday::query()->delete();
        // データがある場合ののみupsert
        if(!empty($upload_data)){
            Holiday::upsert($upload_data, 'holiday');
        }
        return;
    }
}