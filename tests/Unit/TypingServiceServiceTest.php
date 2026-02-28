<?php

declare(strict_types=1);

use App\Models\TypingService;
use App\Services\TypingServiceService;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Move unit tests to Feature context to access Laravel app
uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->service = new TypingServiceService();
});

describe('TypingServiceService', function () {

    describe('list()', function () {
        it('returns all typing services ordered', function () {
            TypingService::factory()->create(['sort_order' => 3]);
            TypingService::factory()->create(['sort_order' => 1]);
            TypingService::factory()->create(['sort_order' => 2]);

            $services = $this->service->list();

            expect($services)->toHaveCount(3);
            expect($services->first()->sort_order)->toBe(1);
            expect($services->last()->sort_order)->toBe(3);
        });

        it('returns empty collection when no services exist', function () {
            $services = $this->service->list();

            expect($services)->toBeEmpty();
        });
    });

    describe('getActive()', function () {
        it('returns only active typing services', function () {
            TypingService::factory()->count(2)->active()->create();
            TypingService::factory()->count(3)->inactive()->create();

            $services = $this->service->getActive();

            expect($services)->toHaveCount(2);
            expect($services->every(fn ($s) => $s->is_active))->toBeTrue();
        });
    });

    describe('getFeatured()', function () {
        it('returns featured active services', function () {
            TypingService::factory()->count(3)->active()->featured()->create();
            TypingService::factory()->count(2)->active()->notFeatured()->create();
            TypingService::factory()->count(2)->inactive()->featured()->create();

            $services = $this->service->getFeatured();

            expect($services)->toHaveCount(3);
            expect($services->every(fn ($s) => $s->is_featured && $s->is_active))->toBeTrue();
        });

        it('respects the limit parameter', function () {
            TypingService::factory()->count(10)->active()->featured()->create();

            $services = $this->service->getFeatured(5);

            expect($services)->toHaveCount(5);
        });
    });

    describe('getBySlug()', function () {
        it('returns service by slug', function () {
            $created = TypingService::factory()->create(['slug' => 'test-service']);

            $found = $this->service->getBySlug('test-service');

            expect($found->id)->toBe($created->id);
            expect($found->slug)->toBe('test-service');
        });

        it('throws exception for non-existent slug', function () {
            $this->service->getBySlug('non-existent');
        })->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    describe('getById()', function () {
        it('returns service by ID', function () {
            $created = TypingService::factory()->create();

            $found = $this->service->getById($created->id);

            expect($found->id)->toBe($created->id);
        });

        it('throws exception for non-existent ID', function () {
            $this->service->getById(9999);
        })->throws(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
    });

    describe('create()', function () {
        it('creates a new typing service', function () {
            $data = [
                'title' => 'New Service',
                'slug' => 'new-service',
                'short_description' => 'Short description',
                'is_active' => true,
            ];

            $service = $this->service->create($data);

            expect($service)->toBeInstanceOf(TypingService::class);
            expect($service->title)->toBe('New Service');
            expect($service->slug)->toBe('new-service');
            expect($service->exists)->toBeTrue();
        });

        it('auto-assigns sort_order if not provided', function () {
            TypingService::factory()->create(['sort_order' => 5]);

            $service = $this->service->create([
                'title' => 'Service Without Order',
                'slug' => 'service-without-order',
            ]);

            expect($service->sort_order)->toBe(6);
        });
    });

    describe('update()', function () {
        it('updates a typing service', function () {
            $service = TypingService::factory()->create(['title' => 'Old Title']);

            $updated = $this->service->update($service, ['title' => 'New Title']);

            expect($updated->title)->toBe('New Title');
            $this->assertDatabaseHas('typing_services', [
                'id' => $service->id,
                'title' => 'New Title',
            ]);
        });
    });

    describe('delete()', function () {
        it('deletes a typing service', function () {
            $service = TypingService::factory()->create();
            $id = $service->id;

            $result = $this->service->delete($service);

            expect($result)->toBeTrue();
            $this->assertDatabaseMissing('typing_services', ['id' => $id]);
        });
    });

    describe('toggleActive()', function () {
        it('toggles active status from true to false', function () {
            $service = TypingService::factory()->create(['is_active' => true]);

            $updated = $this->service->toggleActive($service);

            expect($updated->is_active)->toBeFalse();
        });

        it('toggles active status from false to true', function () {
            $service = TypingService::factory()->create(['is_active' => false]);

            $updated = $this->service->toggleActive($service);

            expect($updated->is_active)->toBeTrue();
        });
    });

    describe('toggleFeatured()', function () {
        it('toggles featured status from true to false', function () {
            $service = TypingService::factory()->create(['is_featured' => true]);

            $updated = $this->service->toggleFeatured($service);

            expect($updated->is_featured)->toBeFalse();
        });

        it('toggles featured status from false to true', function () {
            $service = TypingService::factory()->create(['is_featured' => false]);

            $updated = $this->service->toggleFeatured($service);

            expect($updated->is_featured)->toBeTrue();
        });
    });

    describe('reorder()', function () {
        it('reorders services by given IDs', function () {
            $service1 = TypingService::factory()->create(['sort_order' => 1]);
            $service2 = TypingService::factory()->create(['sort_order' => 2]);
            $service3 = TypingService::factory()->create(['sort_order' => 3]);

            $this->service->reorder([$service3->id, $service1->id, $service2->id]);

            expect($service3->fresh()->sort_order)->toBe(0);
            expect($service1->fresh()->sort_order)->toBe(1);
            expect($service2->fresh()->sort_order)->toBe(2);
        });
    });
});
