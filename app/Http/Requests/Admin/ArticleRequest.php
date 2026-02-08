<?php

namespace App\Http\Requests\Admin;

use App\Enums\PublishStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
        $articleId = $this->route('article')?->id;

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('articles')->ignore($articleId),
            ],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'featured_image' => [
                $this->isMethod('POST') ? 'required' : 'nullable',
                'image',
                'mimes:jpeg,jpg,png,gif,webp,svg,heic,heif',
                'max:5120',
            ],
            'category_id' => ['required', 'exists:article_categories,id'],
            'status' => ['required', Rule::enum(PublishStatus::class)],
            'meta_title' => ['nullable', 'string', 'max:60'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Get custom error messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Article title is required.',
            'content.required' => 'Article content is required.',
            'featured_image.required' => 'Featured image is required.',
            'featured_image.image' => 'Featured image must be an image file.',
            'featured_image.max' => 'Featured image must not exceed 5MB.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'Selected category does not exist.',
            'status.required' => 'Please select a status.',
            'meta_title.max' => 'Meta title should be 60 characters or less for SEO.',
            'meta_description.max' => 'Meta description should be 160 characters or less for SEO.',
        ];
    }
}
