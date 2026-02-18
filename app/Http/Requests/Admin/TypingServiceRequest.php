<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TypingServiceRequest extends FormRequest
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
        $serviceId = $this->route('service')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('typing_services')->ignore($serviceId),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'long_description' => ['nullable', 'string'],
            'icon' => ['nullable', 'string', 'max:100'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif',
                'max:5120',
            ],
            'sub_services' => ['nullable', 'array'],
            'sub_services.*.name' => ['required_with:sub_services', 'string', 'max:255'],
            'sub_services.*.description' => ['nullable', 'string', 'max:500'],
            'sub_services.*.price' => ['nullable', 'string', 'max:100'],
            'sub_services.*.duration' => ['nullable', 'string', 'max:100'],
            'cta_text' => ['nullable', 'string', 'max:100'],
            'cta_link' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The service title is required.',
            'title.max' => 'The service title may not exceed 255 characters.',
            'slug.unique' => 'This slug is already in use.',
            'short_description.max' => 'The short description may not exceed 500 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a valid image file (jpeg, jpg, png, gif, webp, svg, heic, heif).',
            'image.max' => 'The image may not be larger than 5MB.',
            'sub_services.*.name.required_with' => 'Each sub-service must have a name.',
            'meta_title.max' => 'The meta title may not exceed 255 characters.',
            'meta_description.max' => 'The meta description may not exceed 500 characters.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $data = [
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
        ];

        // Filter out empty sub_services (items without name)
        if ($this->has('sub_services')) {
            $subServices = collect($this->input('sub_services'))
                ->filter(fn($item) => !empty(trim($item['name'] ?? '')))
                ->values()
                ->toArray();
            $data['sub_services'] = empty($subServices) ? null : $subServices;
        }

        $this->merge($data);
    }
}
