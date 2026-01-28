<?php

declare(strict_types=1);

use App\Models\TypingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Typing Public Routes', function () {
    describe('Home Page', function () {
        it('can access typing home page', function () {
            $response = $this->get('/typing');

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page->component('typing/typinghome'));
        });

        it('passes services data to home page', function () {
            // Create some typing services
            TypingService::factory()->count(3)->active()->create();

            $response = $this->get('/typing');

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) =>
                $page->component('typing/typinghome')
                    ->has('services')
                    ->has('featuredServices')
                    ->has('settings')
            );
        });
    });

    describe('Services List Page', function () {
        it('can access typing services list page', function () {
            $response = $this->get('/typing/services');

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page->component('typing/typinghome'));
        });
    });

    describe('Contact Page', function () {
        it('can access typing contact page', function () {
            $response = $this->get('/typing/contact');

            $response->assertStatus(200);
            $response->assertInertia(fn ($page) => $page->component('typing/typinghome'));
        });
    });

    describe('Service Detail Pages (Static Vue)', function () {
        $staticServices = [
            'immigration' => 'typing/services/Immigration',
            'labour-ministry' => 'typing/services/LabourMinistry',
            'tasheel-services' => 'typing/services/TasheelServices',
            'domestic-workers-visa' => 'typing/services/DomesticWorkersVisa',
            'family-visa-process' => 'typing/services/FamilyVisaProcess',
            'health-insurance' => 'typing/services/HealthInsurance',
            'ministry-of-interior' => 'typing/services/MinistryOfInterior',
            'certificate-attestation' => 'typing/services/CertificateAttestation',
            'vat-registration' => 'typing/services/VATRegistration',
            'ct-registration' => 'typing/services/CTRegistration',
            'passport-renewal' => 'typing/services/PassportRenewal',
            'immigration-card' => 'typing/services/ImmigrationCard',
        ];

        foreach ($staticServices as $slug => $component) {
            it("can access {$slug} service page", function () use ($slug, $component) {
                $response = $this->get("/typing/services/{$slug}");

                $response->assertStatus(200);
                $response->assertInertia(fn ($page) => $page->component($component));
            });
        }
    });

    describe('Family Visa Sub-Pages', function () {
        $familyPages = [
            'family/new-residency' => 'typing/services/family/NewResidency',
            'family/residency-renewal' => 'typing/services/family/ResidencyRenewal',
            'family/new-born-baby' => 'typing/services/family/NewBornBaby',
            'family/cancellation' => 'typing/services/family/Cancellation',
        ];

        foreach ($familyPages as $path => $component) {
            it("can access {$path} page", function () use ($path, $component) {
                $response = $this->get("/typing/services/{$path}");

                $response->assertStatus(200);
                $response->assertInertia(fn ($page) => $page->component($component));
            });
        }
    });

    describe('Dynamic Service Detail (Database)', function () {
        it('can access service detail via dynamic route from database', function () {
            $service = TypingService::factory()->create([
                'slug' => 'test-service-slug',
                'is_active' => true,
            ]);

            $response = $this->get('/typing/services/test-service-slug');

            // Since 'test-service-slug' is not in static map, it falls back to home
            $response->assertStatus(200);
            $response->assertInertia(fn ($page) =>
                $page->component('typing/typinghome')
                    ->has('service')
            );
        });

        it('returns 404 for non-existent service', function () {
            $response = $this->get('/typing/services/non-existent-service');

            // The controller should throw 404 for non-existent services
            $response->assertStatus(404);
        });
    });
});

describe('Typing Routes Named Routes', function () {
    it('has correct named routes', function () {
        expect(route('typing.index'))->toBe(url('/typing'));
        expect(route('typing.services'))->toBe(url('/typing/services'));
        expect(route('typing.contact'))->toBe(url('/typing/contact'));
        expect(route('typing.service', ['slug' => 'immigration']))->toBe(url('/typing/services/immigration'));
    });
});
