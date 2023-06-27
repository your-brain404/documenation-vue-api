<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessagesRequest extends FormRequest
{
    public function messages()
    {
        return [
            'required' => 'Field \':attribute\' is required!',
            'unique' => 'Field \':attribute\' must to be unique!',
            'same' => 'Field \':attribute\' must be the same as field \':other\'!',
            'min' => 'Field \':attribute\' must be minimum :min characters long!',
            'max' => 'Field \':attribute\' must be maximum :max characters long!'
        ];
    }
}