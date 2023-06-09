<?php

namespace App\Lib;

use App\Models\Tag;
use App\Models\KintaiTag;

class KintaiListFunc
{
    // 所有ロールID毎にタグの数をカウント
    public static function TagCount($kintai_id, $owner_role_id)
    {
        $tag_count = KintaiTag::join('tags', 'tags.tag_id', 'kintai_tags.tag_id')
                        ->where('kintai_tags.kintai_id', $kintai_id)
                        ->where('tags.owner_role_id', $owner_role_id)
                        ->count();
        return $tag_count;
    }
}