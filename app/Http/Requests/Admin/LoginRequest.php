<?php

namespace App\Http\Requests\Admin;

use App\Rules\Admin\Login\PasswordRule;
use App\Rules\Admin\Login\UsernameRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'username' => ['required', new UsernameRule()],
            'password' => ['required', new PasswordRule()],
        ];
    }
}
