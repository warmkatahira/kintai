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
        $schedule->command('backup:create-folder')->monthlyOn(21, '09:49');
        // DBバックアップを取得（毎日AM 03:00）
        $schedule->command('backup:run --disable-notifications --only-db')->dailyAt('09:49');
        // DBバックアップを年月のフォルダへ移動（毎日AM 03:10）
        $schedule->command('backup:move')->dailyAt('09:50');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Commands/CreateBackupFolder.php');
        $this->load(__DIR__.'/Commands/BackupMove.php');

        require base_path('routes/console.php');
    }
}
