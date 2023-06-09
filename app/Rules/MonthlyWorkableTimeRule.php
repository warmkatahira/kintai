<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MonthlyWorkableTimeRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($monthly_workable_time)
    {
        $this->monthly_workable_time  = $monthly_workable_time;
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
        // 入力値が0なら確認する必要がないので確認を終了
        if($this->monthly_workable_time == 0){
            return true;
        }
        // 入力値が数値であることを確認
        if(!is_numeric($this->monthly_workable_time)){
            $this->error_message = "月間稼働設定は数値で入力して下さい。";
            return false;
        }
        // 0.25で割り切れるかを確認
        if(fmod($this->monthly_workable_time, 0.25) != 0){
            $this->error_message = "月間稼働設定は0.25刻みで入力してください。";
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
