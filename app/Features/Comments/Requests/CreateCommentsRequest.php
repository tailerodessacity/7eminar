<?php

namespace App\Features\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|string|min:3|max:10000',
        ];
    }
}
