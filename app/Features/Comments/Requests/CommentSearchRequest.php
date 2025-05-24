<?php

namespace App\Features\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['nullable', 'string', 'min:2'],
            'author' => ['nullable', 'string', 'min:2'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:50'],
            'cursor' => ['nullable', 'string'],
        ];
    }
}
