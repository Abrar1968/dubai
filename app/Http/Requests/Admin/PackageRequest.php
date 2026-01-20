<?php

namespace App\Http\Requests\Admin;

use App\Enums\PackageType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
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
        $packageId = $this->route('package')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('packages')->ignore($packageId),
            ],
            'type' => ['required', Rule::enum(PackageType::class)],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'thumbnail' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5120',
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'discounted_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'duration_nights' => ['required', 'integer', 'min:0'],
            'departure_location' => ['nullable', 'string', 'max:255'],
            'departure_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date', 'after:departure_date'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', 'max:255'],
            'inclusions' => ['nullable', 'array'],
            'inclusions.*' => ['string', 'max:500'],
            'exclusions' => ['nullable', 'array'],
            'exclusions.*' => ['string', 'max:500'],
            'itinerary' => ['nullable', 'array'],
            'itinerary.*.day' => ['required_with:itinerary', 'integer', 'min:1'],
            'itinerary.*.title' => ['required_with:itinerary', 'string', 'max:255'],
            'itinerary.*.description' => ['nullable', 'string'],
            'hotel_details' => ['nullable', 'array'],
            'hotel_details.*.name' => ['required_with:hotel_details', 'string', 'max:255'],
            'hotel_details.*.rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'hotel_details.*.location' => ['nullable', 'string', 'max:255'],
            'hotel_details.*.nights' => ['nullable', 'integer', 'min:0'],
            'max_capacity' => ['nullable', 'integer', 'min:1'],
            'available_slots' => ['nullable', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:15360'],
            'existing_gallery' => ['nullable', 'array'],
            'existing_gallery.*' => ['string'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Package title is required.',
            'type.required' => 'Please select a package type.',
            'thumbnail.required' => 'Package thumbnail is required.',
            'thumbnail.image' => 'Thumbnail must be an image.',
            'thumbnail.max' => 'Thumbnail must not exceed 5MB.',
            'price.required' => 'Package price is required.',
            'discounted_price.lt' => 'Discounted price must be less than the regular price.',
            'duration_days.required' => 'Duration (days) is required.',
            'duration_nights.required' => 'Duration (nights) is required.',
            'return_date.after' => 'Return date must be after departure date.',
            'gallery.*.image' => 'Each gallery item must be an image.',
            'gallery.*.max' => 'Each gallery image must not exceed 15MB.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
