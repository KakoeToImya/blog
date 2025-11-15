<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'content' => [
                'required',
                'min:1',
                'max:2000',
            ],
            'attachment'=>[
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif,pdf,doc,docx',
                'max:2048',
            ],
        ];
    }
}
