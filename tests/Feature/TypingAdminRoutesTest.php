<?php

declare(strict_types=1);

use App\Enums\UserRole;
use App\Models\AdminSection;
use App\Models\TypingService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('public');

    // Create super admin user
    $this->superAdmin = User::factory()->create([
        'role' => UserRole::SUPER_ADMIN,
    ]);

    // Create admin user with typing section access
    $this->typingAdmin = User::factory()->create([
        'role' => UserRole::ADMIN,
    ]);
    AdminSection::create([
        'user_id' => $this->typingAdmin->id,
        'section' => 'typing',
        'assigned_by' => $this->superAdmin->id,
    ]);

    // Create regular user (no admin access)
    $this->regularUser = User::factory()->create([
        'role' => UserRole::USER,
    ]);
});

describe('Admin Typing Services CRUD', function () {

    describe('Index Page', function () {
        it('allows super admin to view services list', function () {
            TypingService::factory()->count(3)->create();

            $response = $this->actingAs($this->superAdmin)
                ->get(route('admin.typing.services.index'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.pages.typing.services.index');
            $response->assertViewHas('services');
            $response->assertViewHas('stats');
        });

        it('allows typing admin to view services list', function () {
            $response = $this->actingAs($this->typingAdmin)
                ->get(route('admin.typing.services.index'));

            $response->assertStatus(200);
        });

        it('denies regular user access to services list', function () {
            $response = $this->actingAs($this->regularUser)
                ->get(route('admin.typing.services.index'));

            $response->assertStatus(403);
        });

        it('redirects unauthenticated user to login', function () {
            $response = $this->get(route('admin.typing.services.index'));

            // Should redirect to some login page (either admin.login or default login)
            $response->assertRedirect();
        });
    });

    describe('Create Page', function () {
        it('allows admin to access create form', function () {
            $response = $this->actingAs($this->superAdmin)
                ->get(route('admin.typing.services.create'));

            $response->assertStatus(200);
            $response->assertViewIs('admin.pages.typing.services.create');
        });
    });

    describe('Store Action', function () {
        it('creates a new typing service', function () {
            $data = [
                'title' => 'Test Service',
                'slug' => 'test-service',
                'short_description' => 'This is a test service',
                'is_active' => true,
            ];

            $response = $this->actingAs($this->superAdmin)
                ->post(route('admin.typing.services.store'), $data);

            $response->assertRedirect(route('admin.typing.services.index'));
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('typing_services', [
                'title' => 'Test Service',
                'slug' => 'test-service',
            ]);
        });

        it('creates service with image upload', function () {
            $image = UploadedFile::fake()->image('service.jpg', 800, 600);

            $data = [
                'title' => 'Service With Image',
                'slug' => 'service-with-image',
                'short_description' => 'Description',
                'is_active' => true,
                'image' => $image,
            ];

            $response = $this->actingAs($this->superAdmin)
                ->post(route('admin.typing.services.store'), $data);

            $response->assertRedirect(route('admin.typing.services.index'));

            $service = TypingService::where('slug', 'service-with-image')->first();
            expect($service)->not->toBeNull();
            expect($service->image)->not->toBeNull();
        });

        it('validates required fields', function () {
            $response = $this->actingAs($this->superAdmin)
                ->post(route('admin.typing.services.store'), []);

            $response->assertSessionHasErrors(['title']);
        });
    });

    describe('Edit Page', function () {
        it('allows admin to access edit form', function () {
            $service = TypingService::factory()->create();

            $response = $this->actingAs($this->superAdmin)
                ->get(route('admin.typing.services.edit', $service));

            $response->assertStatus(200);
            $response->assertViewIs('admin.pages.typing.services.edit');
            $response->assertViewHas('service', $service);
        });
    });

    describe('Update Action', function () {
        it('updates a typing service', function () {
            $service = TypingService::factory()->create(['title' => 'Old Title']);

            $response = $this->actingAs($this->superAdmin)
                ->put(route('admin.typing.services.update', $service), [
                    'title' => 'Updated Title',
                    'slug' => $service->slug,
                    'short_description' => 'Updated description',
                    'is_active' => true,
                ]);

            $response->assertRedirect(route('admin.typing.services.index'));

            $this->assertDatabaseHas('typing_services', [
                'id' => $service->id,
                'title' => 'Updated Title',
            ]);
        });
    });

    describe('Show Page', function () {
        it('displays service details', function () {
            $service = TypingService::factory()->create();

            $response = $this->actingAs($this->superAdmin)
                ->get(route('admin.typing.services.show', $service));

            $response->assertStatus(200);
            $response->assertViewIs('admin.pages.typing.services.show');
            $response->assertViewHas('service', $service);
        });
    });

    describe('Delete Action', function () {
        it('deletes a typing service', function () {
            $service = TypingService::factory()->create();
            $id = $service->id;

            $response = $this->actingAs($this->superAdmin)
                ->delete(route('admin.typing.services.destroy', $service));

            $response->assertRedirect(route('admin.typing.services.index'));

            $this->assertDatabaseMissing('typing_services', ['id' => $id]);
        });
    });

    describe('Toggle Actions', function () {
        it('toggles service active status', function () {
            $service = TypingService::factory()->create(['is_active' => true]);

            $response = $this->actingAs($this->superAdmin)
                ->patch(route('admin.typing.services.toggle-status', $service));

            $response->assertRedirect();

            expect($service->fresh()->is_active)->toBeFalse();
        });

        it('toggles service featured status', function () {
            $service = TypingService::factory()->create(['is_featured' => false]);

            $response = $this->actingAs($this->superAdmin)
                ->patch(route('admin.typing.services.toggle-featured', $service));

            $response->assertRedirect();

            expect($service->fresh()->is_featured)->toBeTrue();
        });
    });

    describe('Reorder Action', function () {
        it('reorders services', function () {
            $service1 = TypingService::factory()->create(['sort_order' => 1]);
            $service2 = TypingService::factory()->create(['sort_order' => 2]);
            $service3 = TypingService::factory()->create(['sort_order' => 3]);

            $response = $this->actingAs($this->superAdmin)
                ->post(route('admin.typing.services.reorder'), [
                    'order' => [$service3->id, $service1->id, $service2->id],
                ]);

            $response->assertRedirect();

            expect($service3->fresh()->sort_order)->toBe(0);
            expect($service1->fresh()->sort_order)->toBe(1);
            expect($service2->fresh()->sort_order)->toBe(2);
        });
    });
});

describe('Admin Typing Settings', function () {
    it('allows super admin to view settings page', function () {
        $response = $this->actingAs($this->superAdmin)
            ->get(route('admin.typing.settings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.pages.typing.settings.index');
    });

    it('allows typing admin to view settings page', function () {
        $response = $this->actingAs($this->typingAdmin)
            ->get(route('admin.typing.settings.index'));

        $response->assertStatus(200);
    });
});
