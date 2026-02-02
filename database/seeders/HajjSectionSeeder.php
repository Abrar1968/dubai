<?php

namespace Database\Seeders;

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
use App\Models\OfficeLocation;
use App\Models\Package;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class HajjSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedPackages();
        $this->seedArticleCategories();
        $this->seedArticles();
        $this->seedTeamMembers();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedOfficeLocations();
        $this->seedSiteSettings();
    }

    protected function seedUsers(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'superadmin@dubai.test'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@dubai.test',
                'password' => Hash::make('password'),
                'role' => UserRole::SUPER_ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Hajj Admin
        $hajjAdmin = User::updateOrCreate(
            ['email' => 'hajjadmin@dubai.test'],
            [
                'name' => 'Hajj Section Admin',
                'email' => 'hajjadmin@dubai.test',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Assign hajj section to admin
        $hajjAdmin->assignedSections()->updateOrCreate(
            ['section' => 'hajj'],
            ['assigned_by' => 1, 'assigned_at' => now()]
        );

        // Regular user
        User::updateOrCreate(
            ['email' => 'user@dubai.test'],
            [
                'name' => 'Test User',
                'email' => 'user@dubai.test',
                'password' => Hash::make('password'),
                'role' => UserRole::USER,
                'email_verified_at' => now(),
            ]
        );
    }

    protected function seedPackages(): void
    {
        $hajjPackages = [
            [
                'title' => 'Premium Hajj Package 2026',
                'slug' => 'premium-hajj-package-2026',
                'type' => PackageType::HAJJ,
                'price' => 8500.00,
                'currency' => 'USD',
                'duration_days' => 21,
                'image' => 'packages/hajjbg.jpg',
                'description' => 'Experience the journey of a lifetime with our Premium Hajj Package. Enjoy 5-star accommodations, VIP transportation, and personalized guidance throughout your spiritual journey.',
                'features' => ['5-Star Hotels', 'VIP Transport', 'Expert Guides', 'All Meals Included', 'Ziyarat Tours', '24/7 Support'],
                'inclusions' => [
                    'Round-trip airfare',
                    '5-star hotel accommodation',
                    'All ground transportation',
                    'Three meals daily',
                    'Hajj visa processing',
                    'Experienced religious guides',
                    'All Ziyarat tours',
                    'Ihram clothing',
                    'Medical insurance'
                ],
                'exclusions' => [
                    'Personal expenses',
                    'Qurbani charges',
                    'Additional baggage fees',
                    'Tips for staff'
                ],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Departure', 'description' => 'Depart from your home city to Jeddah'],
                    ['day' => 2, 'title' => 'Arrival in Makkah', 'description' => 'Transfer to Makkah, check-in to hotel, perform Umrah'],
                    ['day' => '3-7', 'title' => 'Makkah Stay', 'description' => 'Prayers, preparation for Hajj rituals'],
                    ['day' => 8, 'title' => 'Hajj Day 1 (8th Dhul Hijjah)', 'description' => 'Travel to Mina'],
                    ['day' => 9, 'title' => 'Hajj Day 2 (9th Dhul Hijjah)', 'description' => 'Stand at Arafat, proceed to Muzdalifah'],
                    ['day' => 10, 'title' => 'Eid ul-Adha', 'description' => 'Stone Jamarat, Qurbani, Tawaf al-Ifadah'],
                    ['day' => '11-12', 'title' => 'Days of Tashreeq', 'description' => 'Continue stoning, prayers'],
                    ['day' => '13-18', 'title' => 'Madinah Visit', 'description' => 'Ziyarat in Madinah, prayers at Masjid Nabawi'],
                    ['day' => 21, 'title' => 'Return Journey', 'description' => 'Departure to home country']
                ],
                'hotel_details' => [
                    'makkah' => ['name' => 'Swissotel Al Maqam Makkah', 'rating' => 5, 'distance' => '50m from Haram'],
                    'madinah' => ['name' => 'Oberoi Madinah', 'rating' => 5, 'distance' => '100m from Masjid Nabawi']
                ],
                'departure_dates' => ['2026-06-01', '2026-06-05', '2026-06-10'],
                'max_capacity' => 50,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Economy Hajj Package 2026',
                'slug' => 'economy-hajj-package-2026',
                'type' => PackageType::HAJJ,
                'price' => 5500.00,
                'currency' => 'USD',
                'duration_days' => 18,
                'image' => 'packages/hajj.jpg',
                'description' => 'Our affordable Hajj package provides all essential services for your pilgrimage without compromising on quality. Perfect for budget-conscious pilgrims.',
                'features' => ['4-Star Hotels', 'Comfortable Transport', 'Qualified Guides', 'Buffet Meals', 'Ziyarat Tours'],
                'inclusions' => [
                    'Round-trip airfare',
                    '4-star hotel accommodation',
                    'Ground transportation',
                    'Buffet meals',
                    'Hajj visa processing',
                    'Religious guides'
                ],
                'exclusions' => [
                    'Personal expenses',
                    'Qurbani charges',
                    'Additional tours'
                ],
                'itinerary' => [],
                'hotel_details' => [
                    'makkah' => ['name' => 'Le Meridien Makkah', 'rating' => 4, 'distance' => '200m from Haram'],
                    'madinah' => ['name' => 'Movenpick Madinah', 'rating' => 4, 'distance' => '150m from Masjid Nabawi']
                ],
                'departure_dates' => ['2026-06-01', '2026-06-08'],
                'max_capacity' => 100,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Family Hajj Package 2026',
                'slug' => 'family-hajj-package-2026',
                'type' => PackageType::HAJJ,
                'price' => 7500.00,
                'currency' => 'USD',
                'duration_days' => 20,
                'image' => 'packages/family.jpg',
                'description' => 'Specially designed for families, this package offers spacious accommodations, family-friendly services, and activities for all ages.',
                'features' => ['Family Rooms', 'Kids Activities', 'Private Transport', 'All Meals', 'Medical Support'],
                'inclusions' => ['Family suite accommodation', 'Private transportation', 'All meals', 'Kids entertainment', 'Medical staff on call'],
                'exclusions' => ['Personal expenses', 'Qurbani'],
                'itinerary' => [],
                'hotel_details' => [],
                'departure_dates' => ['2026-06-05'],
                'max_capacity' => 40,
                'is_active' => true,
                'is_featured' => false,
            ]
        ];

        $umrahPackages = [
            [
                'title' => 'Ramadan Umrah Special',
                'slug' => 'ramadan-umrah-special',
                'type' => PackageType::UMRAH,
                'price' => 3500.00,
                'currency' => 'USD',
                'duration_days' => 14,
                'image' => 'packages/umrahh.jpg',
                'description' => 'Perform Umrah during the blessed month of Ramadan. Experience the spiritual atmosphere of Makkah and Madinah during this holy month.',
                'features' => ['5-Star Hotels', 'VIP Transport', 'Iftar & Suhoor', 'Ziyarat Tours', '24/7 Support'],
                'inclusions' => [
                    'Round-trip airfare',
                    '5-star hotel in Makkah (7 nights)',
                    '5-star hotel in Madinah (7 nights)',
                    'Private transportation',
                    'Iftar and Suhoor meals',
                    'Umrah visa processing',
                    'Experienced guides'
                ],
                'exclusions' => ['Personal expenses', 'Extra tours', 'Tips'],
                'itinerary' => [
                    ['day' => 1, 'title' => 'Departure', 'description' => 'Flight to Jeddah'],
                    ['day' => 2, 'title' => 'Makkah', 'description' => 'Arrive Makkah, perform Umrah'],
                    ['day' => '3-8', 'title' => 'Makkah Stay', 'description' => 'Prayers, Tahajjud, worship'],
                    ['day' => '9-14', 'title' => 'Madinah', 'description' => 'Visit Masjid Nabawi, Ziyarat'],
                    ['day' => 14, 'title' => 'Return', 'description' => 'Departure from Madinah']
                ],
                'hotel_details' => [
                    'makkah' => ['name' => 'Raffles Makkah Palace', 'rating' => 5, 'distance' => 'Inside Haram complex'],
                    'madinah' => ['name' => 'The Ritz-Carlton Madinah', 'rating' => 5, 'distance' => '50m from Masjid Nabawi']
                ],
                'departure_dates' => ['2026-03-01', '2026-03-08', '2026-03-15'],
                'max_capacity' => 30,
                'is_active' => true,
                'is_featured' => true,
            ],
            [
                'title' => 'Economy Umrah Package',
                'slug' => 'economy-umrah-package',
                'type' => PackageType::UMRAH,
                'price' => 1800.00,
                'currency' => 'USD',
                'duration_days' => 10,
                'image' => 'packages/umrahh.jpg',
                'description' => 'An affordable Umrah package with all essential services. Ideal for first-time pilgrims on a budget.',
                'features' => ['3-Star Hotels', 'Shared Transport', 'Breakfast Included', 'Basic Ziyarat'],
                'inclusions' => ['Airfare', 'Hotel (3-star)', 'Shared transportation', 'Breakfast', 'Visa processing'],
                'exclusions' => ['Lunch & Dinner', 'Personal expenses'],
                'itinerary' => [],
                'hotel_details' => [],
                'departure_dates' => ['2026-02-01', '2026-02-15', '2026-03-01'],
                'max_capacity' => 100,
                'is_active' => true,
                'is_featured' => false,
            ],
            [
                'title' => 'VIP Umrah Experience',
                'slug' => 'vip-umrah-experience',
                'type' => PackageType::UMRAH,
                'price' => 6000.00,
                'currency' => 'USD',
                'duration_days' => 12,
                'image' => 'packages/madina.jpg',
                'description' => 'The ultimate luxury Umrah experience. Stay in the finest hotels, travel in style, and receive personalized attention throughout.',
                'features' => ['5-Star Deluxe Hotels', 'Private Limousine', 'Personal Guide', 'All Meals', 'Executive Services'],
                'inclusions' => ['Business class airfare', 'Royal suite accommodation', 'Private limousine', 'Personal guide', 'All gourmet meals', 'VIP visa processing', 'Exclusive Ziyarat tours'],
                'exclusions' => ['Personal shopping'],
                'itinerary' => [],
                'hotel_details' => [
                    'makkah' => ['name' => 'Four Seasons Makkah', 'rating' => 5, 'distance' => 'Haram view rooms'],
                    'madinah' => ['name' => 'Anwar Al Madinah Mövenpick', 'rating' => 5, 'distance' => 'Adjacent to Masjid']
                ],
                'departure_dates' => ['2026-01-20', '2026-02-20', '2026-03-20'],
                'max_capacity' => 15,
                'is_active' => true,
                'is_featured' => true,
            ]
        ];

        foreach ([...$hajjPackages, ...$umrahPackages] as $package) {
            Package::updateOrCreate(
                ['slug' => $package['slug']],
                $package
            );
        }
    }

    protected function seedArticleCategories(): void
    {
        $categories = [
            ['name' => 'Hajj Guide', 'slug' => 'hajj-guide', 'description' => 'Comprehensive guides for Hajj rituals'],
            ['name' => 'Umrah Guide', 'slug' => 'umrah-guide', 'description' => 'Step-by-step Umrah instructions'],
            ['name' => 'Travel Tips', 'slug' => 'travel-tips', 'description' => 'Essential travel advice'],
            ['name' => 'Islamic History', 'slug' => 'islamic-history', 'description' => 'Historical sites and significance'],
            ['name' => 'Health & Wellness', 'slug' => 'health-wellness', 'description' => 'Stay healthy during pilgrimage'],
        ];

        foreach ($categories as $category) {
            ArticleCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }

    protected function seedArticles(): void
    {
        $articles = [
            [
                'title' => 'Complete Guide to Hajj Rituals 2026',
                'slug' => 'complete-guide-hajj-rituals-2026',
                'excerpt' => 'Learn about all the essential rituals of Hajj, from Ihram to Tawaf al-Wida, with detailed explanations and tips.',
                'featured_image' => 'articles/hajjbg.jpg',
                'content' => '<h2>Understanding Hajj</h2><p>Hajj is one of the Five Pillars of Islam and is obligatory for every Muslim who is physically and financially able to perform it at least once in their lifetime.</p><h2>Day 1: 8th Dhul Hijjah (Yawm al-Tarwiyah)</h2><p>On this day, pilgrims enter the state of Ihram and proceed to Mina, where they spend the night in prayer and preparation.</p><h2>Day 2: 9th Dhul Hijjah (Day of Arafat)</h2><p>This is the most important day of Hajj. Pilgrims proceed to the plains of Arafat for the standing (Wuquf), spending the day in supplication.</p><h2>Day 3: 10th Dhul Hijjah (Eid ul-Adha)</h2><p>Pilgrims throw pebbles at Jamarat al-Aqaba, perform the sacrifice, shave their heads, and perform Tawaf al-Ifadah in Makkah.</p>',
                'category_id' => 1,
                'author_id' => 1,
                'status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(5),
                'meta_title' => 'Complete Hajj Guide 2026',
                'meta_description' => 'Comprehensive guide covering all Hajj rituals.',
            ],
            [
                'title' => 'Umrah Step-by-Step: A Beginner\'s Guide',
                'slug' => 'umrah-step-by-step-beginners-guide',
                'excerpt' => 'First time performing Umrah? This comprehensive guide walks you through every step of the blessed journey.',
                'featured_image' => 'articles/umrahh.jpg',
                'content' => '<h2>What is Umrah?</h2><p>Umrah is often called the "lesser pilgrimage" and can be performed at any time of the year, unlike Hajj which has specific dates.</p><h2>Step 1: Ihram</h2><p>Enter the state of Ihram at the Miqat. Men wear two white unstitched cloths, while women wear modest clothing.</p><h2>Step 2: Tawaf</h2><p>Perform seven circuits around the Kaaba in an anti-clockwise direction, starting from the Black Stone.</p><h2>Step 3: Sai</h2><p>Walk seven times between the hills of Safa and Marwah.</p><h2>Step 4: Halq or Taqsir</h2><p>Men shave their heads (Halq) or trim hair (Taqsir). Women trim a fingertip length of hair.</p>',
                'category_id' => 2,
                'author_id' => 1,
                'status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(3),
                'meta_title' => 'Umrah Guide for Beginners',
                'meta_description' => 'Learn how to perform Umrah step by step.',
            ],
            [
                'title' => 'Essential Packing List for Hajj and Umrah',
                'slug' => 'essential-packing-list-hajj-umrah',
                'excerpt' => 'Don\'t forget anything important! Our comprehensive packing list ensures you\'re fully prepared for your pilgrimage.',
                'featured_image' => 'articles/hajjbg.jpg',
                'content' => '<h2>Documents</h2><ul><li>Valid passport (6+ months validity)</li><li>Visa documents</li><li>Vaccination certificates</li><li>Travel insurance documents</li><li>Hotel booking confirmations</li></ul><h2>Clothing</h2><ul><li>Ihram garments (2-3 sets for men)</li><li>Modest, comfortable clothes</li><li>Comfortable walking shoes</li><li>Socks and slippers</li></ul><h2>Health Items</h2><ul><li>Prescribed medications</li><li>First aid kit</li><li>Sunscreen</li><li>Hand sanitizer</li><li>Face masks</li></ul>',
                'category_id' => 3,
                'author_id' => 1,
                'status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(7),
                'meta_title' => 'Hajj & Umrah Packing List 2026',
                'meta_description' => 'Essential packing checklist for pilgrimage.',
            ],
            [
                'title' => 'The History of Masjid al-Haram',
                'slug' => 'history-masjid-al-haram',
                'excerpt' => 'Explore the rich history of the Grand Mosque in Makkah, from its origins to the magnificent structure we see today.',
                'featured_image' => 'articles/hajjbg.jpg',
                'content' => '<h2>Ancient Origins</h2><p>The Kaaba was originally built by Prophet Ibrahim (AS) and his son Ismail (AS) as the first house of worship dedicated to the One God.</p><h2>Expansion Through History</h2><p>The mosque has undergone numerous expansions throughout Islamic history, from the time of the Caliphs to the Saudi era.</p><h2>Modern Developments</h2><p>Recent expansions have increased the mosque\'s capacity to over 2 million worshippers, with state-of-the-art facilities.</p>',
                'category_id' => 4,
                'author_id' => 1,
                'status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(10),
                'meta_title' => 'History of Masjid al-Haram',
                'meta_description' => 'History of the Grand Mosque in Makkah.',
            ],
            [
                'title' => 'Staying Healthy During Your Pilgrimage',
                'slug' => 'staying-healthy-during-pilgrimage',
                'excerpt' => 'Important health tips to ensure you complete your pilgrimage in good health and full spiritual benefit.',
                'featured_image' => 'articles/umrahh.jpg',
                'content' => '<h2>Before Travel</h2><ul><li>Get all required vaccinations</li><li>Complete health check-up</li><li>Stock up on prescribed medications</li></ul><h2>During Hajj/Umrah</h2><ul><li>Stay hydrated constantly</li><li>Use umbrella during outdoor rituals</li><li>Rest adequately between activities</li><li>Eat balanced, hygienic meals</li></ul><h2>Common Health Concerns</h2><p>Heat exhaustion, foot blisters, and respiratory infections are common. Take preventive measures and seek medical help when needed.</p>',
                'category_id' => 5,
                'author_id' => 1,
                'status' => PublishStatus::PUBLISHED,
                'published_at' => now()->subDays(2),
                'meta_title' => 'Health Tips for Hajj and Umrah',
                'meta_description' => 'Essential health advice for pilgrims.',
            ],
        ];

        foreach ($articles as $article) {
            Article::updateOrCreate(
                ['slug' => $article['slug']],
                $article
            );
        }
    }

    protected function seedTeamMembers(): void
    {
        $members = [
            [
                'name' => 'Sheikh Ahmad Al-Rashid',
                'role' => 'Senior Religious Guide',
                'bio' => 'With over 25 years of experience guiding Hajj groups, Sheikh Ahmad brings deep religious knowledge and practical wisdom to ensure your pilgrimage is spiritually fulfilling.',
                'sort_order' => 1,
                'is_active' => true,
                'social_links' => ['linkedin' => 'https://linkedin.com/in/ahmad-alrashid'],
            ],
            [
                'name' => 'Dr. Fatima Hassan',
                'role' => 'Medical Director',
                'bio' => 'A board-certified physician specialized in travel medicine, Dr. Fatima ensures the health and wellbeing of all our pilgrims throughout their journey.',
                'sort_order' => 2,
                'is_active' => true,
                'social_links' => ['linkedin' => 'https://linkedin.com/in/dr-fatima-hassan'],
            ],
            [
                'name' => 'Mohammed Al-Farouq',
                'role' => 'Operations Manager',
                'bio' => 'Mohammed coordinates all logistics with precision, from airport transfers to hotel arrangements, making sure every aspect of your journey runs smoothly.',
                'sort_order' => 3,
                'is_active' => true,
                'social_links' => ['email' => 'mohammed@dubaihajj.ae'],
            ],
            [
                'name' => 'Amina Yusuf',
                'role' => 'Customer Relations Lead',
                'bio' => 'Amina is your primary point of contact before, during, and after your pilgrimage. She ensures all your questions are answered and concerns addressed.',
                'sort_order' => 4,
                'is_active' => true,
                'social_links' => ['twitter' => 'https://twitter.com/aminayusuf'],
            ],
            [
                'name' => 'Khalid Ibrahim',
                'role' => 'Travel Coordinator',
                'bio' => 'With expertise in Saudi travel regulations and visa processing, Khalid ensures all documentation is handled efficiently and correctly.',
                'sort_order' => 5,
                'is_active' => true,
                'social_links' => [],
            ],
        ];

        foreach ($members as $member) {
            TeamMember::updateOrCreate(
                ['name' => $member['name']],
                $member
            );
        }
    }

    protected function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Abdullah Rahman',
                'location' => 'Dubai, UAE',
                'content' => 'The most spiritual journey of my life! The organization was impeccable, and our guide Sheikh Ahmad made every ritual meaningful. I cannot thank the team enough.',
                'rating' => 5,
                'is_approved' => true,
            ],
            [
                'name' => 'Sarah Ahmed',
                'location' => 'London, UK',
                'content' => 'As a first-time pilgrim, I was nervous about everything. The team made it so easy - from visa processing to daily guidance. Truly a 5-star experience.',
                'rating' => 5,
                'is_approved' => true,
            ],
            [
                'name' => 'Yusuf Okonkwo',
                'location' => 'Lagos, Nigeria',
                'content' => 'The Ramadan Umrah package was beyond expectations. The hotels were excellent, the Iftar arrangements perfect, and the spiritual atmosphere unforgettable.',
                'rating' => 5,
                'is_approved' => true,
            ],
            [
                'name' => 'Mariam Khan',
                'location' => 'Toronto, Canada',
                'content' => 'Traveled with my elderly parents and the team was so accommodating. Wheelchair assistance, special meals, everything was taken care of. Highly recommend!',
                'rating' => 5,
                'is_approved' => true,
            ],
            [
                'name' => 'Hassan Mahmoud',
                'location' => 'Abu Dhabi, UAE',
                'content' => 'Third time booking with this company and they never disappoint. Professional, reliable, and genuinely caring about the pilgrim experience.',
                'rating' => 5,
                'is_approved' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::updateOrCreate(
                ['name' => $testimonial['name'], 'content' => $testimonial['content']],
                $testimonial
            );
        }
    }

    protected function seedFaqs(): void
    {
        $faqs = [
            [
                'question' => 'What documents are required for Hajj visa?',
                'answer' => 'You will need a valid passport with at least 6 months validity, completed visa application form, recent passport-sized photographs, proof of vaccination (meningitis and COVID-19), and for women under 45 traveling without a mahram, a letter from their sponsor.',
                'section' => 'hajj',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'How far in advance should I book my Hajj package?',
                'answer' => 'We recommend booking at least 6 months in advance to secure your preferred package and get the best rates. Hajj visas have limited quotas per country, so early booking increases your chances of securing a spot.',
                'section' => 'hajj',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'Are meals included in the packages?',
                'answer' => 'Yes, most of our packages include meals. Premium packages include all three meals (breakfast, lunch, dinner), while economy packages typically include breakfast. During Ramadan Umrah, Iftar and Suhoor are included.',
                'section' => 'hajj',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What vaccinations are required?',
                'answer' => 'Meningococcal ACWY vaccine is mandatory and must be taken within 3 years and at least 10 days before travel. COVID-19 vaccination may be required. Yellow fever vaccine is required for travelers from endemic countries.',
                'section' => 'hajj',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Can I customize my package?',
                'answer' => 'Absolutely! We offer flexible customization options including room upgrades, extended stays, additional Ziyarat tours, and special dietary requirements. Contact our team to discuss your specific needs.',
                'section' => 'hajj',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'What is your cancellation policy?',
                'answer' => 'Cancellations made 60+ days before departure receive 90% refund. 30-59 days: 50% refund. 15-29 days: 25% refund. Less than 15 days: No refund. Visa fees are non-refundable.',
                'section' => 'hajj',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['question' => $faq['question'], 'section' => $faq['section']],
                $faq
            );
        }
    }

    protected function seedOfficeLocations(): void
    {
        $offices = [
            [
                'name' => 'Dubai Head Office',
                'address' => 'Office 1205, Jumeirah Business Center, JLT, Dubai, UAE',
                'phone' => '+971 4 123 4567',
                'email' => 'info@dubaitravel.ae',
                'section' => 'global',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Abu Dhabi Branch',
                'address' => 'Tower 3, Floor 15, Al Maryah Island, Abu Dhabi, UAE',
                'phone' => '+971 2 987 6543',
                'email' => 'abudhabi@dubaitravel.ae',
                'section' => 'global',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Sharjah Office',
                'address' => 'Al Khan Corniche, Sharjah, UAE',
                'phone' => '+971 6 555 4433',
                'email' => 'sharjah@dubaitravel.ae',
                'section' => 'global',
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($offices as $office) {
            OfficeLocation::updateOrCreate(
                ['name' => $office['name']],
                $office
            );
        }
    }

    protected function seedSiteSettings(): void
    {
        $settings = [
            ['key' => 'company_name', 'value' => 'Dubai Hajj & Umrah Services', 'section' => 'hajj'],
            ['key' => 'company_tagline', 'value' => 'Your Trusted Partner for Sacred Journeys', 'section' => 'hajj'],
            ['key' => 'company_email', 'value' => 'info@dubaihajj.ae', 'section' => 'hajj'],
            ['key' => 'company_phone', 'value' => '+971 4 123 4567', 'section' => 'hajj'],
            ['key' => 'company_whatsapp', 'value' => '+971501234567', 'section' => 'hajj'],
            ['key' => 'company_address', 'value' => 'Jumeirah Business Center, JLT, Dubai, UAE', 'section' => 'hajj'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/dubaihajj', 'section' => 'hajj'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/dubaihajj', 'section' => 'hajj'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/dubaihajj', 'section' => 'hajj'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/dubaihajj', 'section' => 'hajj'],
            ['key' => 'contact_description', 'value' => 'Have questions about our Hajj or Umrah packages? Our team is here to help you plan your spiritual journey. Reach out to us through any of our offices or fill out the contact form.', 'section' => 'hajj'],
            ['key' => 'meta_title', 'value' => 'Dubai Hajj & Umrah Services | Premium Pilgrimage Packages', 'section' => 'hajj'],
            ['key' => 'meta_description', 'value' => 'Book premium Hajj and Umrah packages from Dubai. 5-star hotels, expert guides, all-inclusive services. Your journey to the Holy Land starts here.', 'section' => 'hajj'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key'], 'section' => $setting['section']],
                $setting
            );
        }
    }
}
