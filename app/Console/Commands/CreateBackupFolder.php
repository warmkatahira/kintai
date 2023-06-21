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
    protected $signature = 'backup:create-folder';

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
        /* $currentMonth = CarbonImmutable::now()->format('Y-m');
        Storage::disk('sakura-vps')->makeDirectory("KINTAI/{$currentMonth}"); */

        $currentMonth = CarbonImmutable::now()->format('Y-m');
        $disk = Storage::disk('sakura-vps');
        $directory = "KINTAI/{$currentMonth}";

        // ディレクトリを作成
        $disk->makeDirectory($directory);

        // 所有者を設定 (Apache ユーザーと katahira グループ)
        $owner = 'apache';
        $group = 'katahira';
        $path = $disk->path($directory);
        exec("chown -R {$owner}:{$group} {$path}");

        // パーミッションを設定 (775)
        $permissions = 0775;
        exec("chmod -R {$permissions} {$path}");
    }
}
