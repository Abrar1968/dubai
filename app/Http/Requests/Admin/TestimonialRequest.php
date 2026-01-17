<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TestimonialRequest extends FormRequest
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
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_location' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'package_type' => ['nullable', 'string', 'in:hajj,umrah'],
            'travel_date' => ['nullable', 'date'],
            'is_featured' => ['boolean'],
            'status' => ['nullable', 'string', 'in:pending,approved,rejected'],
        ];

        // Photo required on create, optional on update
        if ($this->isMethod('POST')) {
            $rules['photo'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        } else {
            $rules['photo'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'customer_name' => 'customer name',
            'customer_location' => 'location',
            'content' => 'testimonial content',
            'rating' => 'star rating',
            'package_type' => 'package type',
            'travel_date' => 'travel date',
            'is_featured' => 'featured status',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter the customer\'s name.',
            'content.required' => 'Please enter the testimonial content.',
            'content.max' => 'Testimonial content cannot exceed 2000 characters.',
            'rating.required' => 'Please select a star rating.',
            'rating.min' => 'Rating must be at least 1 star.',
            'rating.max' => 'Rating cannot exceed 5 stars.',
            'photo.max' => 'Photo size cannot exceed 2MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
        ]);
    }
}
