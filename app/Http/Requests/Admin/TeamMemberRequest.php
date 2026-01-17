<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TeamMemberRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'social_links' => ['nullable'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];

        // Photo required on create, optional on update
        if ($this->isMethod('POST')) {
            $rules['photo'] = ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
        } else {
            $rules['photo'] = ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the team member\'s name.',
            'designation.required' => 'Please enter the team member\'s designation.',
            'photo.required' => 'Please upload a photo for the team member.',
            'photo.image' => 'The file must be an image.',
            'photo.max' => 'The photo must not exceed 2MB.',
            'email.email' => 'Please enter a valid email address.',
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
