<?php

namespace App\Features\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'text' => 'required|string|min:3|max:10000',
        ];
    }
}
