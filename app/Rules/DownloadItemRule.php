<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Enums\DataDownloadEnum;

class DownloadItemRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($download_item)
    {
        $this->download_item = $download_item;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Enumに定義してあるダウンロード項目であるかチェック
        $result = DataDownloadEnum::checkKeyExists($this->download_item);
        // falseであれば定義されていない項目なので、処理を中断
        if(!$result){
            $this->error_message = "ダウンロード項目が正しくありません。";
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
