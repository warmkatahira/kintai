<?php

namespace App\Services\EmployeeMgt;

use App\Models\Base;
use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Enums\CommutingAllowanceEnum;
use App\Rules\MonthlyWorkableTimeRule;
use App\Rules\OverTimeStartRule;
use Rap2hpoutre\FastExcel\FastExcel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeCsvService
{
    // 有効/無効の表記
    const AVAILABLE_LIST = [
        1 => '有効',
        0 => '無効',
    ];

    // CSVのヘッダー
    public static function csvHeader()
    {
        return [
            '従業員番号',
            '有効/無効',
            '拠点',
            '従業員区分',
            '従業員名(姓)',
            '従業員名(名)',
            '月間稼働可能時間',
            '残業開始時間',
            '時給単価',
            '有給時稼働時間',
            '交通費支給形態',
            '交通費金額',
        ];
    }

    // ダウンロードする従業員情報を取得(従業員管理の検索条件で絞り込み)
    public function getDownloadData($employees)
    {
        // 拠点名・従業員区分名の対応表を取得
        $base_names = Base::pluck('base_name', 'base_id');
        $category_names = EmployeeCategory::pluck('employee_category_name', 'employee_category_id');
        $download_data = [];
        // 従業員の分だけループ処理
        foreach ($employees->get() as $employee) {
            $download_data[] = [
                '従業員番号' => $employee->employee_no,
                '有効/無効' => self::AVAILABLE_LIST[$employee->is_available] ?? '',
                '拠点' => $base_names[$employee->base_id] ?? '',
                '従業員区分' => $category_names[$employee->employee_category_id] ?? '',
                '従業員名(姓)' => $employee->employee_last_name,
                '従業員名(名)' => $employee->employee_first_name,
                '月間稼働可能時間' => (string)(float)$employee->monthly_workable_time,
                '残業開始時間' => (string)(float)$employee->over_time_start,
                '時給単価' => (string)$employee->hourly_wage,
                '有給時稼働時間' => (string)(float)$employee->paid_leave_working_time,
                '交通費支給形態' => CommutingAllowanceEnum::get_jp($employee->commuting_allowance_type),
                '交通費金額' => (string)$employee->commuting_allowance_amount,
            ];
        }
        // データが空ならヘッダーだけを出力
        if (empty($download_data)) {
            return [array_fill_keys(self::csvHeader(), '')];
        }
        return $download_data;
    }

    public function importData($file)
    {
        // ストレージに保存する際のファイル名を設定
        $name = 'employee_upload_data.csv';
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
        // 取り込まれるべきCSVのヘッダーを配列にセット
        $header = self::csvHeader();
        // ヘッダーを比較し、差分があればエラー情報を返す
        if ($import_data_header === false || !empty(array_diff_assoc($header, $import_data_header)) || count($import_data_header) != count($header)) {
            return 'ヘッダーが正しくない為、アップロードできませんでした。（必要なヘッダー：'.implode('、', $header).'）';
        }
        return null;
    }

    public function setArrayImportData($path)
    {
        // データの情報を取得
        $all_line = (new FastExcel)->import($path);
        // 名称 => IDの対応表を取得
        $base_ids = Base::pluck('base_id', 'base_name');
        $category_ids = EmployeeCategory::pluck('employee_category_id', 'employee_category_name');
        $available_values = array_flip(self::AVAILABLE_LIST);
        $commuting_types = array_flip(CommutingAllowanceEnum::TYPE_LIST);
        // 既存の従業員番号(Excelで先頭の0が消えた番号の検知用)
        $exists_employee_nos = Employee::pluck('employee_no')->flip();
        // アップロード処理に使用する配列をセット
        $upload_data = [];
        $validation_error = [];
        // ファイル内の重複チェック用
        $keys = [];
        // 取得したレコードの分だけループ
        foreach ($all_line as $key => $line) {
            // 行番号(ヘッダー行の分を加算)
            $record_num = $key + 2;
            // UTF-8形式に変換した1行分のデータを取得
            $line = mb_convert_encoding($line, 'UTF-8', 'ASCII, JIS, UTF-8, SJIS-win');
            $line = array_map(fn($value) => trim((string)$value), $line);
            // 空行はスキップ
            if (count(array_filter($line, fn($value) => $value !== '')) == 0) {
                continue;
            }
            // 名称をIDに変換(変換できない場合はnullにしてバリデーションでエラーにする)
            $commuting_type = $line['交通費支給形態'] === '' ? CommutingAllowanceEnum::NONE : ($commuting_types[$line['交通費支給形態']] ?? null);
            $param = [
                'employee_no' => $line['従業員番号'],
                'is_available' => $available_values[$line['有効/無効']] ?? null,
                'base_id' => $base_ids[$line['拠点']] ?? null,
                'employee_category_id' => $category_ids[$line['従業員区分']] ?? null,
                'employee_last_name' => $line['従業員名(姓)'],
                'employee_first_name' => $line['従業員名(名)'],
                'monthly_workable_time' => $line['月間稼働可能時間'] === '' ? 0 : $line['月間稼働可能時間'],
                'over_time_start' => $line['残業開始時間'] === '' ? 0 : $line['残業開始時間'],
                'hourly_wage' => $line['時給単価'] === '' ? 0 : $line['時給単価'],
                'paid_leave_working_time' => $line['有給時稼働時間'] === '' ? 0 : $line['有給時稼働時間'],
                'commuting_allowance_type' => $commuting_type,
                'commuting_allowance_amount' => $line['交通費金額'] === '' ? 0 : $line['交通費金額'],
            ];
            // インポートデータのバリデーション処理
            $message = $this->validationImportData($param, $line, $record_num);
            // エラーメッセージがあればバリデーションエラーを配列に格納
            if (!is_null($message)) {
                $validation_error[] = $message;
                continue;
            }
            // 該当する従業員番号がなく、先頭に0を補うと既存の従業員番号になる場合はエラー
            // (Excelで開いて保存すると「0245」が「245」になるため、別人として追加されるのを防ぐ)
            $padded_employee_no = str_pad($param['employee_no'], 4, '0', STR_PAD_LEFT);
            if (!isset($exists_employee_nos[$param['employee_no']]) && $padded_employee_no !== $param['employee_no'] && isset($exists_employee_nos[$padded_employee_no])) {
                $validation_error[] = $record_num.'行目：従業員番号「'.e($param['employee_no']).'」は存在せず、「'.$padded_employee_no.'」が存在します。Excelで先頭の0が消えていないか確認して下さい。';
                continue;
            }
            // ファイル内で同じ従業員番号が重複していないか確認
            if (isset($keys[$param['employee_no']])) {
                $validation_error[] = $record_num.'行目：'.$keys[$param['employee_no']].'行目と従業員番号が重複しています。';
                continue;
            }
            $keys[$param['employee_no']] = $record_num;
            // パート専用項目(時給単価・有給時稼働時間・交通費)を整理(パート以外は0にする)
            $param = array_merge($param, (new EmployeeMgtService)->getPartTimeParam((object)$param));
            // 追加用の配列に整理した情報を格納
            $upload_data[] = $param;
        }
        return compact('upload_data', 'validation_error');
    }

    public function validationImportData($param, $line, $record_num)
    {
        // バリデーションルールを定義
        $rules = [
            'employee_no' => 'required|max:4',
            'is_available' => 'required',
            'base_id' => 'required',
            'employee_category_id' => 'required',
            'employee_last_name' => 'required|max:10',
            'employee_first_name' => 'required|max:10',
            'monthly_workable_time' => ['required', 'numeric', 'min:0', new MonthlyWorkableTimeRule($param['monthly_workable_time'])],
            'over_time_start' => ['required', 'numeric', 'min:0', new OverTimeStartRule($param['over_time_start'])],
            'hourly_wage' => 'required|integer|min:0|max:99999',
            'paid_leave_working_time' => 'required|numeric|min:0|max:24|multiple_of:0.25',
            'commuting_allowance_type' => ['required', Rule::in(array_keys(CommutingAllowanceEnum::TYPE_LIST))],
            'commuting_allowance_amount' => 'required|integer|min:0|max:999999',
        ];
        // バリデーションエラーメッセージを定義
        $messages = [
            'required' => ":attributeは必須です。",
            'employee_no.max' => ":attributeは:max文字以内で入力して下さい。",
            'employee_last_name.max' => ":attributeは:max文字以内で入力して下さい。",
            'employee_first_name.max' => ":attributeは:max文字以内で入力して下さい。",
            'numeric' => ":attributeは数値で入力して下さい。",
            'integer' => ":attributeは整数で入力して下さい。",
            'min' => ":attributeは:min以上で入力して下さい。",
            'hourly_wage.max' => ":attributeは:max以下で入力して下さい。",
            'paid_leave_working_time.max' => ":attributeは:max以下で入力して下さい。",
            'multiple_of' => ":attributeは:value単位で入力して下さい。",
            'commuting_allowance_amount.max' => ":attributeは:max以下で入力して下さい。",
            'in' => ":attributeの値が不正です。",
        ];
        // バリデーションエラー項目を定義
        $attributes = [
            'employee_no' => '従業員番号',
            'is_available' => '有効/無効',
            'base_id' => '拠点',
            'employee_category_id' => '従業員区分',
            'employee_last_name' => '従業員名(姓)',
            'employee_first_name' => '従業員名(名)',
            'monthly_workable_time' => '月間稼働可能時間',
            'over_time_start' => '残業開始時間',
            'hourly_wage' => '時給単価',
            'paid_leave_working_time' => '有給時稼働時間',
            'commuting_allowance_type' => '交通費支給形態',
            'commuting_allowance_amount' => '交通費金額',
        ];
        // バリデーション実施
        $validator = Validator::make($param, $rules, $messages, $attributes);
        $errors = collect($validator->errors()->toArray())->map(fn($errors) => $errors[0]);
        // 名称が入力されているのに変換できなかった項目は、入力値を含めたメッセージにする
        $name_columns = [
            'is_available' => ['有効/無効', '有効または無効で入力して下さい。'],
            'base_id' => ['拠点', '拠点名が存在しません。'],
            'employee_category_id' => ['従業員区分', '従業員区分名が存在しません。'],
            'commuting_allowance_type' => ['交通費支給形態', implode('/', CommutingAllowanceEnum::TYPE_LIST).'のいずれかで入力して下さい。'],
        ];
        foreach ($name_columns as $column => [$csv_column, $message]) {
            if (is_null($param[$column]) && $line[$csv_column] !== '') {
                // 入力値はアラート(JS文字列)に埋め込まれるためエスケープする
                $errors[$column] = $csv_column.'「'.e($line[$csv_column]).'」：'.$message;
            }
        }
        // エラーがなければnullを返す
        if ($errors->isEmpty()) {
            return null;
        }
        return $record_num.'行目：'.$errors->implode(' / ');
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

    // 従業員更新処理(従業員番号が存在すれば更新、存在しなければ追加)
    public function upsertEmployee($upload_data)
    {
        $create_count = 0;
        $update_count = 0;
        // 既存の従業員を取得
        $exists = Employee::whereIn('employee_no', array_column($upload_data, 'employee_no'))->get()->keyBy('employee_no');
        foreach ($upload_data as $param) {
            $employee = $exists->get($param['employee_no']);
            // 存在しなければ追加
            if (is_null($employee)) {
                Employee::create($param);
                $create_count++;
                continue;
            }
            // 存在すれば更新
            $employee->update($param);
            $update_count++;
        }
        return compact('create_count', 'update_count');
    }
}
