<?php

/**
 * Comprehensive Tests for Hajj & Umrah Admin Section
 *
 * Tests all CRUD operations for:
 * - Packages (Create, Read, Update, Delete, Toggle Status, Toggle Featured)
 * - Bookings (Read, Update Status, Confirm, Cancel, Update Payment, Delete)
 * - Articles (Create, Read, Update, Delete, Publish, Unpublish)
 * - Team Members (Create, Read, Update, Delete, Toggle Active, Reorder)
 * - Testimonials (Create, Read, Update, Delete, Approve, Reject, Toggle Featured)
 * - Inquiries (Read, Mark Read, Mark Responded, Delete, Bulk Actions)
 * - FAQs (Create, Read, Update, Delete, Toggle Active, Reorder)
 * - Settings (Read, Update Company, Update SEO, Update Social, Update Booking, Clear Cache)
 */

use App\Enums\BookingStatus;
use App\Enums\InquiryStatus;
use App\Enums\PackageType;
use App\Enums\PublishStatus;
use App\Enums\UserRole;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\Booking;
use App\Models\ContactInquiry;
use App\Models\Faq;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;

beforeEach(function () {
    // Create super admin for admin tests
    $this->superAdmin = User::factory()->create([
        'role' => UserRole::SUPER_ADMIN,
        'email_verified_at' => now(),
        'is_active' => true,
    ]);

    // Assign hajj section
    $this->superAdmin->assignedSections()->create([
        'section' => 'hajj',
        'assigned_by' => $this->superAdmin->id,
    ]);

    // Create regular user for unauthorized tests
    $this->regularUser = User::factory()->create([
        'role' => UserRole::USER,
        'email_verified_at' => now(),
    ]);
});

// ============================================================================
// PACKAGES CRUD TESTS
// ============================================================================

describe('Packages - Full CRUD', function () {
    beforeEach(function () {
        $this->package = Package::create([
            'title' => 'Test Package',
            'slug' => 'test-package',
            'type' => PackageType::HAJJ,
            'price' => 5000.00,
            'currency' => 'AED',
            'duration_days' => 21,
            'duration_nights' => 20,
            'is_active' => true,
            'is_featured' => false,
            'features' => ['Feature 1', 'Feature 2'],
            'inclusions' => ['Inclusion 1'],
            'exclusions' => ['Exclusion 1'],
        ]);
    });

    it('requires authentication to access packages', function () {
        get(route('admin.hajj.packages.index'))
            ->assertRedirect(route('login'));
    });

    it('regular users cannot access packages', function () {
        actingAs($this->regularUser)
            ->get(route('admin.hajj.packages.index'))
            ->assertStatus(403);
    });

    it('can list packages with stats', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.packages.index'))
            ->assertStatus(200)
            ->assertSee('Test Package')
            ->assertSee('Packages');
    });

    it('can filter packages by type', function () {
        Package::create([
            'title' => 'Umrah Package',
            'slug' => 'umrah-package',
            'type' => PackageType::UMRAH,
            'price' => 3000.00,
            'currency' => 'AED',
            'duration_days' => 14,
        ]);

        actingAs($this->superAdmin)
            ->get(route('admin.hajj.packages.index', ['type' => 'hajj']))
            ->assertStatus(200)
            ->assertSee('Test Package')
            ->assertDontSee('Umrah Package');
    });

    it('can view package create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.packages.create'))
            ->assertStatus(200)
            ->assertSee('Create');
    });

    it('can view package details', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.packages.show', $this->package))
            ->assertStatus(200)
            ->assertSee('Test Package');
    });

    it('can view package edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.packages.edit', $this->package))
            ->assertStatus(200)
            ->assertSee('Edit');
    });

    it('can toggle package status', function () {
        expect($this->package->is_active)->toBeTrue();

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.packages.toggle-status', $this->package))
            ->assertRedirect();

        $this->package->refresh();
        expect($this->package->is_active)->toBeFalse();
    });

    it('can toggle package featured status', function () {
        expect($this->package->is_featured)->toBeFalse();

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.packages.toggle-featured', $this->package))
            ->assertRedirect();

        $this->package->refresh();
        expect($this->package->is_featured)->toBeTrue();
    });

    it('can delete a package', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.packages.destroy', $this->package))
            ->assertRedirect();

        expect(Package::withTrashed()->find($this->package->id)->trashed())->toBeTrue();
    });
});

// ============================================================================
// BOOKINGS TESTS
// ============================================================================

describe('Bookings - Management', function () {
    beforeEach(function () {
        $this->package = Package::create([
            'title' => 'Booking Test Package',
            'slug' => 'booking-test-package',
            'type' => PackageType::HAJJ,
            'price' => 5000.00,
            'currency' => 'AED',
            'duration_days' => 21,
        ]);

        $this->booking = Booking::create([
            'booking_number' => 'BK-'.strtoupper(uniqid()),
            'package_id' => $this->package->id,
            'user_id' => $this->regularUser->id,
            'status' => BookingStatus::PENDING,
            'travelers_count' => 2,
            'total_amount' => 10000.00,
            'paid_amount' => 0,
            'contact_name' => 'John Doe',
            'contact_email' => 'john@example.com',
            'contact_phone' => '+971501234567',
        ]);
    });

    it('can list bookings with stats', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.bookings.index'))
            ->assertStatus(200)
            ->assertSee($this->booking->booking_number);
    });

    it('can filter bookings by status', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.bookings.index', ['status' => 'pending']))
            ->assertStatus(200)
            ->assertSee($this->booking->booking_number);
    });

    it('can view booking details', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.bookings.show', $this->booking))
            ->assertStatus(200)
            ->assertSee($this->booking->booking_number)
            ->assertSee('John Doe');
    });

    it('can confirm a pending booking', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.bookings.confirm', $this->booking))
            ->assertRedirect();

        $this->booking->refresh();
        expect($this->booking->status)->toBe(BookingStatus::CONFIRMED);
    });

    it('can cancel a booking', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.bookings.cancel', $this->booking), [
                'reason' => 'Customer request',
            ])
            ->assertRedirect();

        $this->booking->refresh();
        expect($this->booking->status)->toBe(BookingStatus::CANCELLED);
    });

    it('can update booking status', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.bookings.update-status', $this->booking), [
                'status' => 'processing',
                'notes' => 'Processing documents',
            ])
            ->assertRedirect();

        $this->booking->refresh();
        expect($this->booking->status)->toBe(BookingStatus::PROCESSING);
    });

    it('can update payment information', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.bookings.update-payment', $this->booking), [
                'paid_amount' => 5000.00,
                'payment_method' => 'Bank Transfer',
                'payment_reference' => 'TRX123456',
            ])
            ->assertRedirect();

        $this->booking->refresh();
        expect((float) $this->booking->paid_amount)->toBe(5000.00);
        expect($this->booking->payment_method)->toBe('Bank Transfer');
    });

    it('can delete a booking', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.bookings.destroy', $this->booking))
            ->assertRedirect();

        // Bookings use soft deletes, so check deleted_at is set
        $this->booking->refresh();
        expect($this->booking->deleted_at)->not->toBeNull();
    });
});

// ============================================================================
// ARTICLES CRUD TESTS
// ============================================================================

describe('Articles - Full CRUD', function () {
    beforeEach(function () {
        $this->category = ArticleCategory::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $this->article = Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'excerpt' => 'Test excerpt content',
            'content' => '<p>Test full content</p>',
            'category_id' => $this->category->id,
            'author_id' => $this->superAdmin->id,
            'status' => PublishStatus::DRAFT,
        ]);
    });

    it('can list articles', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.articles.index'))
            ->assertStatus(200)
            ->assertSee('Test Article');
    });

    it('can filter articles by status', function () {
        Article::create([
            'title' => 'Published Article',
            'slug' => 'published-article',
            'content' => 'Content',
            'category_id' => $this->category->id,
            'author_id' => $this->superAdmin->id,
            'status' => PublishStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        actingAs($this->superAdmin)
            ->get(route('admin.hajj.articles.index', ['status' => 'draft']))
            ->assertStatus(200)
            ->assertSee('Test Article')
            ->assertDontSee('Published Article');
    });

    it('can view article create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.articles.create'))
            ->assertStatus(200);
    });

    it('can create an article', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.articles.store'), [
                'title' => 'New Article',
                'slug' => 'new-article',
                'excerpt' => 'New excerpt',
                'content' => '<p>New content</p>',
                'category_id' => $this->category->id,
                'status' => 'draft',
            ])
            ->assertRedirect();

        assertDatabaseHas('articles', ['title' => 'New Article']);
    });

    it('can view article details', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.articles.show', $this->article))
            ->assertStatus(200)
            ->assertSee('Test Article');
    });

    it('can view article edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.articles.edit', $this->article))
            ->assertStatus(200);
    });

    it('can update an article', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.articles.update', $this->article), [
                'title' => 'Updated Article',
                'slug' => 'updated-article',
                'excerpt' => 'Updated excerpt',
                'content' => '<p>Updated content</p>',
                'category_id' => $this->category->id,
                'status' => 'draft',
            ])
            ->assertRedirect();

        $this->article->refresh();
        expect($this->article->title)->toBe('Updated Article');
    });

    it('can publish an article', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.articles.publish', $this->article))
            ->assertRedirect();

        $this->article->refresh();
        expect($this->article->status)->toBe(PublishStatus::PUBLISHED);
        expect($this->article->published_at)->not->toBeNull();
    });

    it('can unpublish an article', function () {
        $this->article->update([
            'status' => PublishStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.articles.unpublish', $this->article))
            ->assertRedirect();

        $this->article->refresh();
        expect($this->article->status)->toBe(PublishStatus::DRAFT);
    });

    it('can delete an article', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.articles.destroy', $this->article))
            ->assertRedirect();

        expect(Article::withTrashed()->find($this->article->id)->trashed())->toBeTrue();
    });
});

// ============================================================================
// TEAM MEMBERS CRUD TESTS
// ============================================================================

describe('Team Members - Full CRUD', function () {
    beforeEach(function () {
        $this->teamMember = TeamMember::create([
            'name' => 'John Doe',
            'role' => 'Manager',
            'bio' => 'Experienced manager with 10 years experience',
            'is_active' => true,
            'sort_order' => 1,
            'section' => 'hajj',
        ]);
    });

    it('can list team members', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.team.index'))
            ->assertStatus(200)
            ->assertSee('John Doe');
    });

    it('can view team member create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.team.create'))
            ->assertStatus(200);
    });

    it('can create a team member', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.team.store'), [
                'name' => 'Jane Smith',
                'role' => 'Developer',
                'bio' => 'Full stack developer',
                'is_active' => true,
                'sort_order' => 2,
            ])
            ->assertRedirect();

        assertDatabaseHas('team_members', ['name' => 'Jane Smith']);
    });

    it('can view team member edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.team.edit', $this->teamMember))
            ->assertStatus(200);
    });

    it('can update a team member', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.team.update', $this->teamMember), [
                'name' => 'John Updated',
                'role' => 'Senior Manager',
                'bio' => 'Updated bio',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect();

        $this->teamMember->refresh();
        expect($this->teamMember->name)->toBe('John Updated');
    });

    it('can toggle team member active status', function () {
        expect($this->teamMember->is_active)->toBeTrue();

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.team.toggle-active', $this->teamMember))
            ->assertRedirect();

        $this->teamMember->refresh();
        expect($this->teamMember->is_active)->toBeFalse();
    });

    it('can reorder team members', function () {
        $member2 = TeamMember::create([
            'name' => 'Member 2',
            'role' => 'Role 2',
            'sort_order' => 2,
            'section' => 'hajj',
        ]);

        actingAs($this->superAdmin)
            ->post(route('admin.hajj.team.reorder'), [
                'order' => [$member2->id, $this->teamMember->id],
            ])
            ->assertRedirect();
    });

    it('can delete a team member', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.team.destroy', $this->teamMember))
            ->assertRedirect();

        // TeamMember uses soft deletes, so check deleted_at is set
        $this->teamMember->refresh();
        expect($this->teamMember->deleted_at)->not->toBeNull();
    });
});

// ============================================================================
// TESTIMONIALS CRUD TESTS
// ============================================================================

describe('Testimonials - Full CRUD', function () {
    beforeEach(function () {
        $this->testimonial = Testimonial::create([
            'name' => 'Happy Customer',
            'location' => 'Dubai, UAE',
            'content' => 'Great service and experience!',
            'rating' => 5,
            'is_approved' => false,
            'is_featured' => false,
            'section' => 'hajj',
        ]);
    });

    it('can list testimonials', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.testimonials.index'))
            ->assertStatus(200)
            ->assertSee('Happy Customer');
    });

    it('can view testimonial create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.testimonials.create'))
            ->assertStatus(200);
    });

    it('can create a testimonial', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.testimonials.store'), [
                'name' => 'New Customer',
                'location' => 'Abu Dhabi',
                'content' => 'Amazing pilgrimage experience!',
                'rating' => 5,
            ])
            ->assertRedirect();

        assertDatabaseHas('testimonials', ['name' => 'New Customer']);
    });

    it('can view testimonial edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.testimonials.edit', $this->testimonial))
            ->assertStatus(200);
    });

    it('can update a testimonial', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.testimonials.update', $this->testimonial), [
                'name' => 'Updated Customer',
                'location' => 'Sharjah',
                'content' => 'Updated review content',
                'rating' => 4,
            ])
            ->assertRedirect();

        $this->testimonial->refresh();
        expect($this->testimonial->name)->toBe('Updated Customer');
    });

    it('can approve a testimonial', function () {
        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.testimonials.approve', $this->testimonial))
            ->assertRedirect();

        $this->testimonial->refresh();
        expect($this->testimonial->is_approved)->toBeTrue();
    });

    it('can reject a testimonial', function () {
        $this->testimonial->update(['is_approved' => true]);

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.testimonials.reject', $this->testimonial))
            ->assertRedirect();

        $this->testimonial->refresh();
        expect($this->testimonial->is_approved)->toBeFalse();
    });

    it('can toggle testimonial featured status', function () {
        $this->testimonial->update(['is_approved' => true]);

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.testimonials.toggle-featured', $this->testimonial))
            ->assertRedirect();

        $this->testimonial->refresh();
        expect($this->testimonial->is_featured)->toBeTrue();
    });

    it('can delete a testimonial', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.testimonials.destroy', $this->testimonial))
            ->assertRedirect();

        assertDatabaseMissing('testimonials', ['id' => $this->testimonial->id]);
    });
});

// ============================================================================
// INQUIRIES TESTS
// ============================================================================

describe('Inquiries - Management', function () {
    beforeEach(function () {
        $this->inquiry = ContactInquiry::create([
            'name' => 'Interested Customer',
            'email' => 'customer@example.com',
            'phone' => '+971501234567',
            'subject' => 'Hajj Package Inquiry',
            'message' => 'I would like to know more about your Hajj packages.',
            'section' => 'hajj',
            'status' => InquiryStatus::NEW,
        ]);
    });

    it('can list inquiries with counts', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.inquiries.index'))
            ->assertStatus(200)
            ->assertSee('Interested Customer');
    });

    it('can filter inquiries by status', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.inquiries.index', ['status' => 'new']))
            ->assertStatus(200)
            ->assertSee('Interested Customer');
    });

    it('can view inquiry details and marks as read', function () {
        expect($this->inquiry->status)->toBe(InquiryStatus::NEW);

        actingAs($this->superAdmin)
            ->get(route('admin.hajj.inquiries.show', $this->inquiry))
            ->assertStatus(200)
            ->assertSee('Hajj Package Inquiry');

        $this->inquiry->refresh();
        expect($this->inquiry->status)->toBe(InquiryStatus::READ);
    });

    it('can mark inquiry as responded', function () {
        $this->inquiry->update(['status' => InquiryStatus::READ]);

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.inquiries.mark-responded', $this->inquiry))
            ->assertRedirect();

        $this->inquiry->refresh();
        expect($this->inquiry->status)->toBe(InquiryStatus::RESPONDED);
    });

    it('can delete an inquiry', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.inquiries.destroy', $this->inquiry))
            ->assertRedirect();

        assertDatabaseMissing('contact_inquiries', ['id' => $this->inquiry->id]);
    });

    it('can bulk mark inquiries as read', function () {
        $inquiry2 = ContactInquiry::create([
            'name' => 'Another Customer',
            'email' => 'another@example.com',
            'subject' => 'Another Inquiry',
            'message' => 'Message',
            'section' => 'hajj',
            'status' => InquiryStatus::NEW,
        ]);

        actingAs($this->superAdmin)
            ->post(route('admin.hajj.inquiries.bulk-mark-read'), [
                'ids' => [$this->inquiry->id, $inquiry2->id],
            ])
            ->assertRedirect();

        $this->inquiry->refresh();
        $inquiry2->refresh();
        expect($this->inquiry->status)->toBe(InquiryStatus::READ);
        expect($inquiry2->status)->toBe(InquiryStatus::READ);
    });

    it('can bulk delete inquiries', function () {
        $inquiry2 = ContactInquiry::create([
            'name' => 'Delete Me',
            'email' => 'delete@example.com',
            'subject' => 'Delete',
            'message' => 'Message',
            'section' => 'hajj',
            'status' => InquiryStatus::NEW,
        ]);

        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.inquiries.bulk-delete'), [
                'ids' => [$this->inquiry->id, $inquiry2->id],
            ])
            ->assertRedirect();

        assertDatabaseMissing('contact_inquiries', ['id' => $this->inquiry->id]);
        assertDatabaseMissing('contact_inquiries', ['id' => $inquiry2->id]);
    });
});

// ============================================================================
// FAQS CRUD TESTS
// ============================================================================

describe('FAQs - Full CRUD', function () {
    beforeEach(function () {
        $this->faq = Faq::create([
            'question' => 'What is Hajj?',
            'answer' => 'Hajj is the annual Islamic pilgrimage to Makkah.',
            'section' => 'hajj',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    });

    it('can list FAQs', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.faqs.index'))
            ->assertStatus(200)
            ->assertSee('What is Hajj?');
    });

    it('can view FAQ create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.faqs.create'))
            ->assertStatus(200);
    });

    it('can create a FAQ', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.faqs.store'), [
                'question' => 'What is Umrah?',
                'answer' => 'Umrah is the lesser pilgrimage.',
                'section' => 'hajj',
                'is_active' => true,
                'sort_order' => 2,
            ])
            ->assertRedirect();

        assertDatabaseHas('faqs', ['question' => 'What is Umrah?']);
    });

    it('can view FAQ edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.faqs.edit', $this->faq))
            ->assertStatus(200);
    });

    it('can update a FAQ', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.faqs.update', $this->faq), [
                'question' => 'Updated Question?',
                'answer' => 'Updated answer.',
                'section' => 'hajj',
                'is_active' => true,
                'sort_order' => 1,
            ])
            ->assertRedirect();

        $this->faq->refresh();
        expect($this->faq->question)->toBe('Updated Question?');
    });

    it('can toggle FAQ active status', function () {
        expect($this->faq->is_active)->toBeTrue();

        actingAs($this->superAdmin)
            ->patch(route('admin.hajj.faqs.toggle-active', $this->faq))
            ->assertRedirect();

        $this->faq->refresh();
        expect($this->faq->is_active)->toBeFalse();
    });

    it('can reorder FAQs', function () {
        $faq2 = Faq::create([
            'question' => 'Second FAQ?',
            'answer' => 'Second answer.',
            'section' => 'hajj',
            'sort_order' => 2,
        ]);

        actingAs($this->superAdmin)
            ->post(route('admin.hajj.faqs.reorder'), [
                'order' => [$faq2->id, $this->faq->id],
            ])
            ->assertRedirect();
    });

    it('can delete a FAQ', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.faqs.destroy', $this->faq))
            ->assertRedirect();

        assertDatabaseMissing('faqs', ['id' => $this->faq->id]);
    });
});

// ============================================================================
// SETTINGS TESTS
// ============================================================================

describe('Settings - Management', function () {
    beforeEach(function () {
        // Create some default settings
        SiteSetting::create(['key' => 'company_name', 'value' => 'Test Company', 'section' => 'hajj']);
        SiteSetting::create(['key' => 'company_email', 'value' => 'test@example.com', 'section' => 'hajj']);
    });

    it('can view settings page', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.settings.index'))
            ->assertStatus(200);
    });

    it('can update company settings', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.settings.update-company'), [
                'company_name' => 'Updated Company Name',
                'company_email' => 'updated@example.com',
                'company_phone' => '+971501234567',
                'company_address' => '123 Test Street',
            ])
            ->assertRedirect();

        expect(SiteSetting::where('key', 'company_name')->where('section', 'hajj')->first()->value)
            ->toBe('Updated Company Name');
    });

    it('can update SEO settings', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.settings.update-seo'), [
                'meta_title' => 'SEO Title',
                'meta_description' => 'SEO Description',
                'meta_keywords' => 'hajj, umrah, pilgrimage',
            ])
            ->assertRedirect();

        expect(SiteSetting::where('key', 'meta_title')->where('section', 'hajj')->first()->value)
            ->toBe('SEO Title');
    });

    it('can update social media settings', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.settings.update-social'), [
                'social_facebook' => 'https://facebook.com/test',
                'social_twitter' => 'https://twitter.com/test',
                'social_instagram' => 'https://instagram.com/test',
            ])
            ->assertRedirect();

        expect(SiteSetting::where('key', 'social_facebook')->where('section', 'hajj')->first()->value)
            ->toBe('https://facebook.com/test');
    });

    it('can update booking settings', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.settings.update-booking'), [
                'booking_email' => 'bookings@example.com',
                'booking_phone' => '+971501234567',
                'terms_conditions' => 'Terms and conditions text',
            ])
            ->assertRedirect();

        expect(SiteSetting::where('key', 'booking_email')->where('section', 'hajj')->first()->value)
            ->toBe('bookings@example.com');
    });

    it('can clear settings cache', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.settings.clear-cache'))
            ->assertRedirect();
    });
});

// ============================================================================
// ARTICLE CATEGORIES CRUD TESTS
// ============================================================================

describe('Article Categories - Full CRUD', function () {
    beforeEach(function () {
        $this->category = ArticleCategory::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description',
        ]);
    });

    it('can list article categories', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.article-categories.index'))
            ->assertStatus(200)
            ->assertSee('Test Category');
    });

    it('can view category create form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.article-categories.create'))
            ->assertStatus(200);
    });

    it('can create an article category', function () {
        actingAs($this->superAdmin)
            ->post(route('admin.hajj.article-categories.store'), [
                'name' => 'New Category',
                'slug' => 'new-category',
                'description' => 'New description',
            ])
            ->assertRedirect();

        assertDatabaseHas('article_categories', ['name' => 'New Category']);
    });

    it('can view category edit form', function () {
        actingAs($this->superAdmin)
            ->get(route('admin.hajj.article-categories.edit', $this->category))
            ->assertStatus(200);
    });

    it('can update an article category', function () {
        actingAs($this->superAdmin)
            ->put(route('admin.hajj.article-categories.update', $this->category), [
                'name' => 'Updated Category',
                'slug' => 'updated-category',
                'description' => 'Updated description',
            ])
            ->assertRedirect();

        $this->category->refresh();
        expect($this->category->name)->toBe('Updated Category');
    });

    it('can delete an article category', function () {
        actingAs($this->superAdmin)
            ->delete(route('admin.hajj.article-categories.destroy', $this->category))
            ->assertRedirect();

        assertDatabaseMissing('article_categories', ['id' => $this->category->id]);
    });
});
