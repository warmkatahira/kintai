<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use App\Models\Kintai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\KintaiCheckMail;

class CheckKintai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:kintai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // 配列をセット
        $data = [];
        // 先月の月初と月末の日付を取得
        $start_day = CarbonImmutable::now()->subMonth()->startOfMonth()->toDateString();
        $end_day = CarbonImmutable::now()->subMonth()->endOfMonth()->toDateString();
        // 同日・同従業員の勤怠が複数ないか確認
        $last_month_kintais = $this->getSameKintai($start_day, $end_day);
        // 先月の勤怠でエラーがあれば配列にセット
        $data = $this->setErrorData($data, $last_month_kintais);
        // 今月の月初と月末の日付を取得
        $start_day = CarbonImmutable::now()->startOfMonth()->toDateString();
        $end_day = CarbonImmutable::now()->endOfMonth()->toDateString();
        // 同日・同従業員の勤怠が複数ないか確認
        $current_month_kintais = $this->getSameKintai($start_day, $end_day);
        // 今月の勤怠でエラーがあれば配列にセット
        $data = $this->setErrorData($data, $current_month_kintais);
        // 配列が空でなければエラーがあるので、メールを送信
        if(!empty($data)){
            Mail::send(new KintaiCheckMail($data));
        }
    }

    // 同日・同従業員の勤怠が複数ないか確認
    public function getSameKintai($start_day, $end_day)
    {
        // 指定した期間で同日・同従業員の勤怠が複数ないか取得
        return Kintai::whereDate('work_day', '>=', $start_day)
                    ->join('employees', 'employees.employee_id', 'kintais.employee_id')
                    ->join('bases', 'bases.base_id', 'employees.base_id')
                    ->whereDate('work_day', '<=', $end_day)
                    ->select('work_day', 'kintais.employee_id', 'base_name', 'employee_last_name', 'employee_first_name')
                    ->groupBy('work_day', 'kintais.employee_id', 'base_name', 'employee_last_name', 'employee_first_name')
                    ->having(DB::raw('COUNT(*)'), '>', 1)
                    ->get();
    }

    // 勤怠でエラーがあれば配列にセット
    public function setErrorData($data, $kintais)
    {
        // 取得した結果があれば
        if($kintais->count() > 0){
            // 配列に情報を格納
            foreach($kintais as $kintai){
                $data[] = [
                    'base_name' => $kintai->base_name,
                    'employee_name' => $kintai->employee_last_name.' '.$kintai->employee_first_name,
                    'work_day' => CarbonImmutable::parse($kintai->work_day)->format('Y年m月d日'),
                    'message' => '勤怠が重複しています',
                ];
            }
        }
        return $data;
    }
}
