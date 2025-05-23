<?php

namespace App\Features\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentSearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => ['nullable', 'string'],
            'author' => ['nullable', 'string'],
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
