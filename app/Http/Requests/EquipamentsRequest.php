<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipamentsRequest extends FormRequest
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
            "name" => ["required"],
            "number_ports" => ["required"]
        ];
    }

    public function messages()
    {
        return [
            "name" => "O campo 'name' é necessário.",
            "number_ports" => "O campo 'number_ports' é necessário.",
        ];
    }
}
