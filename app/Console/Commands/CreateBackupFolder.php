<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;

class CreateBackupFolder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-backup-folder';

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
        $currentMonth = CarbonImmutable::now()->format('Y-m');
        Storage::disk('sakura-vps')->makeDirectory("backups/{$currentMonth}");
    }
}
