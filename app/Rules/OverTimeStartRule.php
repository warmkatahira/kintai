<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OverTimeStartRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($over_time_start_setting)
    {
        $this->over_time_start_setting  = $over_time_start_setting;
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
        // 入力値が0かNullなら確認する必要がないので確認を終了
        if($this->over_time_start_setting == 0 || is_null($this->over_time_start_setting)){
            return true;
        }
        // 入力値が数値であることを確認
        if(!is_numeric($this->over_time_start_setting)){
            $this->error_message = "残業開始時間設定は数値で入力して下さい。";
            return false;
        }
        // 0.25で割り切れるかを確認
        if(fmod($this->over_time_start_setting, 0.25) != 0){
            $this->error_message = "残業開始時間設定は0.25刻みで入力してください。";
            return false;
        };
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
