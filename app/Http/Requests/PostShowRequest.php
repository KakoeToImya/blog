<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'highlight_comment' => [
                'nullable',
                'integer',
                'min:1',
            ]
        ];
    }


}
