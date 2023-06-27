<?php

namespace App\Http\Requests;


class RegisterRequest extends CustomMessagesRequest
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
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|same:passwordConfirm',
            'passwordConfirm' => 'required|min:8|same:password'
        ];
    }

    public function messages()
    {
        return array_merge(parent::messages(), [
            'email.unique' => 'This user already exists!',
        ]);
    }
}