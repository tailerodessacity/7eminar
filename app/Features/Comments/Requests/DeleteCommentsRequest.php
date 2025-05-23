<?php

namespace App\Features\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteCommentsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'is_approved' => 'boolean',
        ];
    }
}
