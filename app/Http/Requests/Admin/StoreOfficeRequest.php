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
            'office.fantasy_name' => ['nullable', 'string', 'max:255'],
            'office.cnpj' => ['required', 'string', 'max:32'],
            'office.phone' => ['nullable', 'string', 'max:32'],
            'office.email' => ['nullable', 'email', 'max:255'],
            'office.street' => ['nullable', 'string', 'max:255'],
            'office.number' => ['nullable', 'string', 'max:32'],
            'office.city' => ['nullable', 'string', 'max:255'],
            'office.state' => ['nullable', 'string', 'max:32'],
            'office.zip_code' => ['nullable', 'string', 'max:32'],
            'office.is_active' => ['sometimes', 'boolean'],
            'office.current_plan' => ['nullable', 'string', 'max:64'],

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
            'office.cnpj.required' => 'O CNPJ é obrigatório.',
            'user.name.required' => 'O nome do usuário é obrigatório.',
            'user.email.required' => 'O email do usuário é obrigatório.',
            'user.password.required' => 'A senha do usuário é obrigatória.',
        ];
    }
}
