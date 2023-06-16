<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\DownloadItemRule;

class DataDownloadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'base_id' => 'required|exists:bases,base_id',
            'date' => 'required|date',
            'download_item' => ['required', new DownloadItemRule($this->download_item)],
        ];
    }

    public function messages()
    {
        return [
            'required' => ":attributeは必須です。",
            'date' => ':attributeが正しくありません。',
            'exists' => ':attributeが存在しません。',
        ];
    }

    public function attributes()
    {
        return [
            'base_id' => '拠点',
            'date' => 'ダウンロード年月',
            'download_item' => 'ダウンロード項目',
        ];
    }
}
