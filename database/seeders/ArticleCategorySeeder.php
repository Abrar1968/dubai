<?php

namespace Database\Seeders;

use App\Models\ArticleCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hajj Guides',
                'description' => 'Comprehensive guides and tips for performing Hajj pilgrimage.',
            ],
            [
                'name' => 'Umrah Tips',
                'description' => 'Helpful tips and advice for Umrah pilgrims.',
            ],
            [
                'name' => 'Travel Preparation',
                'description' => 'Everything you need to know before your journey.',
            ],
            [
                'name' => 'Religious Practices',
                'description' => 'Information about rituals, prayers, and spiritual practices.',
            ],
            [
                'name' => 'Accommodation & Hotels',
                'description' => 'Reviews and information about hotels near the holy sites.',
            ],
            [
                'name' => 'News & Updates',
                'description' => 'Latest news about Hajj, Umrah, and travel regulations.',
            ],
        ];

        foreach ($categories as $category) {
            ArticleCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                ]
            );
        }

        $this->command->info('Article categories seeded: ' . count($categories) . ' categories');
    }
}
