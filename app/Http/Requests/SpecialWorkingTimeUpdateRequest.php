<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// モデル
use App\Models\Kintai;

class SpecialWorkingTimeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        // 勤怠を取得
        $kintai = Kintai::getSpecify($this->kintai_id)->first();
        return [
            'special_working_time' => [
                'nullable',
                'integer',
                'min:15',
                'max:'.(int)$kintai->working_time, 
                function($attribute, $value, $fail){
                    // まず数値かチェック（numeric &整数の組み合わせに似た挙動）
                    if(!filter_var($value, FILTER_VALIDATE_INT)){
                        return;
                    }
                    if($value % 15 !== 0) {
                        $fail('15分単位で入力して下さい。');
                    }
                },],
        ];
    }

    public function messages()
    {
        return [
            'max' => ":attributeは:max分以下で入力して下さい。",
            'min' => ":attributeは:min分以上で入力して下さい。",
            'integer' => ":attributeは整数で入力して下さい。",
        ];
    }

    public function attributes()
    {
        return [
            'special_working_time' => '特別稼働時間',
        ];
    }
}
