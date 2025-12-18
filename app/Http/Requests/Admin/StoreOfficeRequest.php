<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfficeRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Middleware already restricts access; keep true here
        return true;
    }

    public function rules(): array
    {
        return [
            // Office data
            'office.name' => ['required', 'string', 'max:255'],
            'office.fantasy_name' => ['required', 'string', 'max:255'],
            'office.cnpj' => ['required', 'string', 'regex:/^\d{14}$/', 'unique:offices,cnpj'],
            'office.phone' => ['required', 'string', 'max:32'],
            'office.email' => ['required', 'email', 'max:255', 'unique:offices,email'],
            'office.street' => ['required', 'string', 'max:255'],
            'office.number' => ['required', 'string', 'max:32'],
            'office.city' => ['required', 'string', 'max:255'],
            'office.state' => ['required', 'string', 'max:32'],
            'office.zip_code' => ['required', 'string', 'max:32'],
            'office.is_active' => ['sometimes', 'boolean'],
            'office.current_plan' => ['required', 'string', 'max:64'],

            // First user (tenant owner)
            'user.name' => ['required', 'string', 'max:255'],
            'user.email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'user.password' => ['required', 'string', 'min:8'],
            // Only tenant roles are valid for the first office user
            'user.role' => ['sometimes', Rule::in(UserRole::tenantRoles())],
        ];
    }

    public function messages(): array
    {
        return [
            'office.name.required' => 'O nome do escritório é obrigatório.',
            'office.fantasy_name.required' => 'O nome fantasia é obrigatório.',
            'office.cnpj.required' => 'O CNPJ é obrigatório.',
            'office.cnpj.regex' => 'O CNPJ deve conter 14 dígitos (apenas números).',
            'office.cnpj.unique' => 'Este CNPJ já está em uso por outro escritório.',
            'office.phone.required' => 'O telefone é obrigatório.',
            'office.email.required' => 'O email do escritório é obrigatório.',
            'office.email.unique' => 'Este email já está em uso por outro escritório.',
            'office.street.required' => 'A rua é obrigatória.',
            'office.number.required' => 'O número é obrigatório.',
            'office.city.required' => 'A cidade é obrigatória.',
            'office.state.required' => 'O estado é obrigatório.',
            'office.zip_code.required' => 'O CEP é obrigatório.',
            'office.current_plan.required' => 'O plano atual é obrigatório.',
            'user.name.required' => 'O nome do usuário é obrigatório.',
            'user.email.required' => 'O email do usuário é obrigatório.',
            'user.password.required' => 'A senha do usuário é obrigatória.',
        ];
    }
}
