<?php

namespace Database\Factories;

use App\Models\TypingService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TypingService>
 */
class TypingServiceFactory extends Factory
{
    protected $model = TypingService::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => ucwords($title),
            'slug' => Str::slug($title),
            'short_description' => fake()->sentence(10),
            'long_description' => fake()->paragraphs(3, true),
            'icon' => 'fas fa-file-alt',
            'image' => null,
            'sub_services' => [
                ['name' => fake()->words(2, true), 'price' => fake()->numberBetween(50, 500)],
                ['name' => fake()->words(2, true), 'price' => fake()->numberBetween(50, 500)],
            ],
            'cta_text' => fake()->randomElement(['Learn More', 'Get Started', 'Contact Us']),
            'cta_link' => '#',
            'sort_order' => fake()->numberBetween(1, 100),
            'is_active' => true,
            'is_featured' => false,
            'meta_title' => ucwords($title),
            'meta_description' => fake()->sentence(15),
        ];
    }

    /**
     * Indicate that the service is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the service is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the service is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the service is not featured.
     */
    public function notFeatured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => false,
        ]);
    }

    /**
     * Set specific slug for the service.
     */
    public function withSlug(string $slug): static
    {
        return $this->state(fn (array $attributes) => [
            'slug' => $slug,
        ]);
    }
}
