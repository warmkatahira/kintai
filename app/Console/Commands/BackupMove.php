<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BackupMove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:move';

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
        // 保存場所とファイルの情報を取得
        $disk = Storage::disk('sakura-vps');
        $files = $disk->files('KINTAI');
        // ファイルの分だけループ処理
        foreach ($files as $file) {
            // ファイル名を取得
            $filename = basename($file);
            // ファイル名から年月を取得
            $date = substr($filename, 0, 7);
            // $dateと同じ名前のフォルダへファイルを移動
            $disk->move('KINTAI/'.$filename, 'KINTAI/'.$date.'/'.$filename);
        }
    }
}
