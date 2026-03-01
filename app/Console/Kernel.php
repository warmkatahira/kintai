<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // DBバックアップを格納するフォルダを作成（毎月1日AM 00:00）
        $schedule->command('backup:create-folder')->monthlyOn(1, '00:00');
        // DBバックアップを取得（毎日AM 03:00）
        $schedule->exec(
            'mysqldump -u katahira -pkatahira134 kintai | gzip > /var/backup/kintai/db/normal/kintai_$(date +\%Y\%m\%d_\%H\%M\%S).sql.gz'
        )->dailyAt('19:58');
        // DBバックアップを年月のフォルダへ移動（毎日AM 03:10）
        $schedule->command('backup:move')->dailyAt('03:10');
        // 当月分の勤怠を確認し、異常があればメールを送信（毎日AM 04:00）
        $schedule->command('check:kintai')->dailyAt('04:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Commands/CreateBackupFolder.php');
        $this->load(__DIR__.'/Commands/BackupMove.php');
        $this->load(__DIR__.'/Commands/CheckKintai.php');

        require base_path('routes/console.php');
    }
}
