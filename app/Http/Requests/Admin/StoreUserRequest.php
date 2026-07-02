<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in([UserRole::Admin->value, UserRole::Perti->value, UserRole::UnitKerja->value])],
            'perti_id' => [
                'required_if:role,' . UserRole::UnitKerja->value,
                'nullable',
                Rule::exists('users', 'id')->where('role', UserRole::Perti->value)
            ],
        ];
    }
}
