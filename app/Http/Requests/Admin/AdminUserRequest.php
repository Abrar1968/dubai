<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only super_admin can manage admin users
        return auth()->user()->isSuperAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->admin?->id),
            ],
            'role' => ['required', Rule::enum(UserRole::class), Rule::in([UserRole::ADMIN->value, UserRole::SUPER_ADMIN->value])],
            'sections' => ['nullable', 'array'],
            'sections.*' => ['string', 'in:hajj,tour,typing'],
            'is_active' => ['boolean'],
        ];

        // Password required on create, optional on update
        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        } else {
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'full name',
            'email' => 'email address',
            'role' => 'user role',
            'password' => 'password',
            'sections' => 'section assignments',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the admin\'s name.',
            'email.required' => 'Please enter an email address.',
            'email.unique' => 'This email is already in use.',
            'role.required' => 'Please select a role.',
            'password.required' => 'Please enter a password.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
