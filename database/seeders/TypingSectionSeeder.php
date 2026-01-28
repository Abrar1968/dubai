<?php

namespace Database\Seeders;

use App\Models\FamilyVisaEmirate;
use App\Models\FamilyVisaType;
use App\Models\SiteSetting;
use App\Models\TypingService;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TypingSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedTypingAdmin();
        $this->seedTypingServices();
        $this->seedFamilyVisaData();
        $this->seedTypingSettings();
    }

    /**
     * Seed typing section admin.
     */
    protected function seedTypingAdmin(): void
    {
        // Create typing admin if not exists
        $typingAdmin = User::updateOrCreate(
            ['email' => 'typingadmin@dubai.test'],
            [
                'name' => 'Typing Section Admin',
                'email' => 'typingadmin@dubai.test',
                'password' => Hash::make('password'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Assign typing section to admin
        $typingAdmin->assignedSections()->updateOrCreate(
            ['section' => 'typing'],
            ['assigned_by' => 1, 'assigned_at' => now()]
        );
    }

    /**
     * Seed typing services (matching Vue file content exactly).
     */
    protected function seedTypingServices(): void
    {
        $services = [
            // 1. Immigration
            [
                'title' => 'Immigration',
                'slug' => 'immigration',
                'short_description' => 'Emirates ID, residency and immigration document services.',
                'long_description' => 'We help individuals and companies with UAE immigration and residency processes.',
                'icon' => '🛂',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Establishment card', 'description' => 'Register your company as a legal sponsor. Requirements typically include a valid trade licence, company documents and passport copies of authorized signatories. The Establishment Card is required for processing visas and labour services.'],
                    ['name' => 'NEW VISA APPLY', 'description' => 'We handle new visa applications (employment, family, visit, residency). Process includes sponsor documentation, entry permits, medical checks and visa stamping. Timing and document lists depend on visa type.'],
                    ['name' => 'Medical (fitness test)', 'description' => 'Coordination of mandatory medical fitness tests (blood tests, chest X-ray). Results are required for residency approvals and Emirates ID issuance.'],
                    ['name' => 'Emirates ID', 'description' => 'Assistance with Emirates ID enrolment and biometric appointments (photo + fingerprints). The ID is linked to residency status and required for many official transactions.'],
                    ['name' => 'RESIDENCE', 'description' => 'Support for residency permit issuance, renewals and cancellations. We provide guidance on validity, renewal windows and documentation to avoid processing delays.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 1,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Immigration Services | Dubai Typing Services',
                'meta_description' => 'Emirates ID, residency and immigration document services in UAE.',
            ],
            // 2. Labour Ministry
            [
                'title' => 'Labour Ministry',
                'slug' => 'labour-ministry',
                'short_description' => 'MOHRE and labour-related transactions and queries.',
                'long_description' => 'Assistance with MOHRE and labour-related processes for employers and employees.',
                'icon' => '💼',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Ministry Of Human Resource / Business Setup', 'description' => 'Employer registration and company setup guidance. We help link company records with MOHRE so you can hire staff, issue labour contracts and apply for work permits.'],
                    ['name' => 'Trade licences issue and renewal (Economic Department Services)', 'description' => 'Support for trade licence issuance and renewals required for business continuity and employee sponsorship. We prepare and submit renewal documentation to the relevant economic departments.'],
                    ['name' => 'LABOUR UPDATE', 'description' => 'Manage employee records: salary updates, contract amendments, transfers and terminations to keep government records up to date and compliant.'],
                    ['name' => 'LABOUR QUOTA APPLY', 'description' => 'Apply for additional foreign worker quotas. We assess eligibility and prepare quota applications to improve chances of approval based on government policies.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 2,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Labour Ministry Services | MOHRE Services Dubai',
                'meta_description' => 'MOHRE and labour-related transactions and queries in UAE.',
            ],
            // 3. Tasheel Services
            [
                'title' => 'Tasheel Services',
                'slug' => 'tasheel-services',
                'short_description' => 'Tasheel centre transactions, contract typing and document submission.',
                'long_description' => 'Services provided at Tasheel service centres including labour transactions and contract typing.',
                'icon' => '🏢',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Service Centre Transactions', 'description' => 'Document submission, fee payments and status tracking for labour-related transactions processed through Tasheel centres.'],
                    ['name' => 'Contract Typing', 'description' => 'Prepare and type employment contracts to meet MOHRE requirements and ensure accurate records for sponsorship and visa applications.'],
                    ['name' => 'Appointments & Follow-ups', 'description' => 'We coordinate appointments and follow up with authorities to expedite approvals and resolve queries related to Tasheel services.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 3,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Tasheel Services Dubai | Government Documentation',
                'meta_description' => 'Tasheel centre transactions, contract typing and document submission.',
            ],
            // 4. Domestic Workers Visa
            [
                'title' => 'Domestic Workers Visa',
                'slug' => 'domestic-workers-visa',
                'short_description' => 'Visa processing and documentation for domestic workers.',
                'long_description' => 'Support for sponsoring and applying for domestic worker visas including documentation and sponsor obligations.',
                'icon' => '🏠',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Eligibility & Requirements', 'description' => 'Guidance on eligibility, required documents, sponsor responsibilities and welfare obligations for domestic workers.'],
                    ['name' => 'Application Process', 'description' => 'Assistance with submission of visa applications, sponsor declarations, and tracking of entry permits and stamping procedures.'],
                    ['name' => 'Medical & Emirates ID', 'description' => 'Coordinate mandatory medical tests and Emirates ID enrolment where applicable, ensuring compliance with residency requirements.'],
                    ['name' => 'Sponsor Responsibilities', 'description' => 'Advice on employer duties, contract terms, and regulations to protect both sponsor and worker rights.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 4,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Domestic Workers Visa Dubai | Maid Visa UAE',
                'meta_description' => 'Visa processing and documentation for domestic workers in UAE.',
            ],
            // 5. Family Visa
            [
                'title' => 'Family Visa',
                'slug' => 'family-visa-process',
                'short_description' => 'Family visa flow, renewals, newborn registration and cancellations.',
                'long_description' => 'Your Gateway to Reuniting Families. We understand the significance of family and the joy that comes with being together. Our mission is to simplify the visa application process, making it easier for families to reunite and create lasting memories.',
                'icon' => '👨‍👩‍👧‍👦',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Apply for New Residency / Entry Permit', 'description' => 'Start a new family residency application with sponsor documents, entry permits and medical fitness tests.'],
                    ['name' => 'Residency Renewal', 'description' => 'Renew existing family residency permits before expiry to avoid penalties.'],
                    ['name' => 'New Born Baby', 'description' => 'Register and sponsor newborns with required birth documentation and medical certificates.'],
                    ['name' => 'Cancellation', 'description' => 'Assist with visa cancellations and related clearance if the sponsor requests it.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 5,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Family Visa Services UAE | Spouse & Dependent Visa Dubai',
                'meta_description' => 'Family visa flow, renewals, newborn registration and cancellations in UAE.',
            ],
            // 6. Health Insurance
            [
                'title' => 'Health Insurance',
                'slug' => 'health-insurance',
                'short_description' => 'Medical fitness and health insurance coordination for visa processing.',
                'long_description' => 'Provision of mandatory and voluntary health insurance policies for residents and employees.',
                'icon' => '❤️',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Policy Types', 'description' => 'Guidance on selecting third-party or comprehensive plans suitable for individuals, families or corporate groups.'],
                    ['name' => 'Issuance & Renewal', 'description' => 'Assistance with policy issuance, renewals, and ensuring compliant coverage as per local regulations.'],
                    ['name' => 'Claims Assistance', 'description' => 'Support through claims processing and documentation to speed up reimbursements and approvals.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 6,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Health Insurance Dubai | Medical Insurance UAE',
                'meta_description' => 'Medical fitness and health insurance coordination for visa processing.',
            ],
            // 7. Ministry of Interior
            [
                'title' => 'Ministry of Interior',
                'slug' => 'ministry-of-interior',
                'short_description' => 'MOI-related permits, Emirates ID and clearance services.',
                'long_description' => 'Key services provided through the Ministry of Interior: police clearance, vehicle services, insurance and driving licence support.',
                'icon' => '🛡️',
                'image' => null,
                'sub_services' => [
                    ['name' => 'POLICE CLEARANCE CERTIFICATE', 'description' => 'Obtain good conduct certificates required for employment and residency. The process usually requires identity verification and an online application; timelines vary by nationality and whether additional attestations are needed.'],
                    ['name' => 'VEHICLE REGISTRATION', 'description' => 'Vehicle registration, renewal and ownership transfers via the traffic department. Required documents typically include valid insurance and vehicle inspection reports where applicable.'],
                    ['name' => 'Car Insurance', 'description' => 'Mandatory insurance (third-party or comprehensive) for vehicle usage. We can help source competitive quotes and complete insurance documentation for registration or renewal.'],
                    ['name' => 'Driving License issue and renewal', 'description' => 'Support with driving licence issuance, renewals and conversions. Processes may include eye tests, medical fitness checks and theory/practical tests depending on applicant nationality and license type.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 7,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Ministry of Interior Services | MOI Services Dubai',
                'meta_description' => 'MOI-related permits, Emirates ID and clearance services in UAE.',
            ],
            // 8. Certificate Attestation
            [
                'title' => 'Certificate Attestation',
                'slug' => 'certificate-attestation',
                'short_description' => 'Document attestation and legalisation services.',
                'long_description' => 'Document attestation and legalization services for personal and corporate certificates.',
                'icon' => '📋',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Document Collection & Verification', 'description' => 'Assist with collecting required documents, verifying signatures and preparing files for attestation.'],
                    ['name' => 'Notary & MOFA Attestation', 'description' => 'Handle notary attestations and Ministry of Foreign Affairs processing where required for cross-border recognition.'],
                    ['name' => 'Embassy Legalization', 'description' => 'Proceed with embassy-level legalization for documents needing recognition in foreign jurisdictions.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 8,
                'is_active' => true,
                'is_featured' => true,
                'meta_title' => 'Certificate Attestation Dubai | Document Attestation UAE',
                'meta_description' => 'Document attestation and legalisation services in UAE.',
            ],
            // 9. VAT Registration
            [
                'title' => 'VAT Registration',
                'slug' => 'vat-registration',
                'short_description' => 'VAT registration and tax compliance assistance.',
                'long_description' => 'Registration and ongoing compliance services for Value Added Tax (VAT) regimes.',
                'icon' => '🧮',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Registration Process', 'description' => 'Guidance on VAT registration thresholds, document requirements, and submission to the tax authority.'],
                    ['name' => 'Compliance & Filing', 'description' => 'Assistance with VAT return preparation, filing schedules and record-keeping best practices.'],
                    ['name' => 'VAT Advisory', 'description' => 'Advice on VAT treatment for goods and services, exemptions and cross-border transactions.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 9,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'VAT Registration UAE | VAT Filing Services Dubai',
                'meta_description' => 'VAT registration and tax compliance assistance in UAE.',
            ],
            // 10. CT Registration
            [
                'title' => 'CT Registration',
                'slug' => 'ct-registration',
                'short_description' => 'Corporate tax (CT) registration and support.',
                'long_description' => 'Commercial / corporate tax registration and compliance support services.',
                'icon' => '🏛️',
                'image' => null,
                'sub_services' => [
                    ['name' => 'CT Registration Steps', 'description' => 'Assistance with registration steps, submission of required documents and initial tax setup for businesses.'],
                    ['name' => 'Filing & Compliance', 'description' => 'Help with tax filings, accounting records and aligning with CT regulations to remain compliant.'],
                    ['name' => 'Penalties & Remedies', 'description' => 'Advisory on penalties and remedial actions in case of non-compliance and late filings.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 10,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Corporate Tax Registration UAE | CT Services Dubai',
                'meta_description' => 'Corporate tax (CT) registration and support in UAE.',
            ],
            // 11. Passport Renewal
            [
                'title' => 'Passport Renewal',
                'slug' => 'passport-renewal',
                'short_description' => 'Passport renewal guidance and document handling.',
                'long_description' => 'Assistance with passport renewal for residents and citizens, including document collection and processing.',
                'icon' => '🪪',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Eligibility & Requirements', 'description' => 'Information on eligibility, required documents, and conditions for passport renewal or extension.'],
                    ['name' => 'Renewal Process', 'description' => 'Support with online submissions or in-person appointments, tracking application status and receiving renewed passports.'],
                    ['name' => 'Emergency Travel Documents', 'description' => 'Assistance with emergency or temporary travel documents where immediate travel is required.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 11,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Passport Renewal Dubai | Passport Services UAE',
                'meta_description' => 'Passport renewal guidance and document handling in UAE.',
            ],
            // 12. Immigration Card
            [
                'title' => 'Immigration Card',
                'slug' => 'immigration-card',
                'short_description' => 'Immigration card and Emirates ID assistance and replacement.',
                'long_description' => 'Services related to the issuance, renewal and replacement of immigration/residency cards.',
                'icon' => '💳',
                'image' => null,
                'sub_services' => [
                    ['name' => 'Card Issuance', 'description' => 'Assistance with initial issuance and first-time enrolment for immigration/residency cards, including biometrics coordination.'],
                    ['name' => 'Replacement & Renewal', 'description' => 'Support for lost card replacement, renewals and updating expiry-related documentation.'],
                    ['name' => 'Linking & Services', 'description' => 'Help linking the card to residency records, health insurance and other government services where needed.'],
                ],
                'cta_text' => 'Apply',
                'cta_link' => '/contactus',
                'sort_order' => 12,
                'is_active' => true,
                'is_featured' => false,
                'meta_title' => 'Immigration Card Services UAE | Smart Card Dubai',
                'meta_description' => 'Immigration card and Emirates ID assistance and replacement in UAE.',
            ],
        ];

        foreach ($services as $serviceData) {
            TypingService::updateOrCreate(
                ['slug' => $serviceData['slug']],
                $serviceData
            );
        }
    }

    /**
     * Seed Family Visa Emirates and Visa Types.
     * This populates the dynamic family visa system with all 7 UAE emirates
     * and their respective visa types (New Residency, Renewal, New Born, Cancellation).
     */
    protected function seedFamilyVisaData(): void
    {
        $emirates = [
            [
                'name' => 'Sharjah',
                'slug' => 'sharjah',
                'description' => 'Sharjah processes family visas in accordance with federal immigration rules and local authority procedures. Processing times vary and some emirate-specific requirements may apply.',
                'intro_text' => 'Complete family visa services for Sharjah residents and sponsors.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Dubai',
                'slug' => 'dubai',
                'description' => 'Dubai follows federal visa rules and has specific timelines for medical testing and Emirates ID issuance. We coordinate appointments and document submissions to comply with Dubai-specific procedures.',
                'intro_text' => 'Professional family visa assistance for Dubai sponsors.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ajman',
                'slug' => 'ajman',
                'description' => 'Ajman processes follow the federal guidelines with local administrative steps; we assist with document submission and local office visits.',
                'intro_text' => 'Family visa support for Ajman-based sponsors.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Abu Dhabi',
                'slug' => 'abu-dhabi',
                'description' => 'Large-scale processing hub with specific health insurance and medical checks; we ensure all steps are completed accurately for Abu Dhabi authorities.',
                'intro_text' => 'Comprehensive family visa services in the capital.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Ras Al Khaimah',
                'slug' => 'ras-al-khaimah',
                'description' => 'Ras Al Khaimah follows federal immigration processes and we offer end-to-end support for family visa matters in the emirate.',
                'intro_text' => 'Family visa assistance for RAK residents.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Umm Al Quwain',
                'slug' => 'umm-al-quwain',
                'description' => 'We assist with family visas in Umm Al Quwain, including coordination of medical tests and Emirates ID steps.',
                'intro_text' => 'Family visa support in Umm Al Quwain.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Fujairah',
                'slug' => 'fujairah',
                'description' => 'Fujairah family visa processing support with local knowledge to speed up document handling and approvals.',
                'intro_text' => 'Expert family visa services for Fujairah sponsors.',
                'sort_order' => 7,
                'is_active' => true,
            ],
        ];

        // Common visa types for all emirates
        $visaTypes = [
            [
                'name' => 'Apply for New Residency / Entry Permit',
                'slug' => 'new-residency',
                'short_description' => 'Start a new family residency application with sponsor documents, entry permits and medical fitness tests.',
                'long_description' => '<p>Start a new family residency application with sponsor documents, entry permits and medical fitness tests. We guide you through each step and coordinate appointments for Emirates ID and medical fitness tests to minimize delays.</p><p>Our experienced team ensures all documentation is prepared correctly, reducing the risk of rejection or processing delays.</p>',
                'requirements' => [
                    'Valid sponsor residency visa',
                    'Original passport of family member',
                    'Passport-size photographs',
                    'Marriage certificate (attested)',
                    'Birth certificates for children (attested)',
                    'Sponsor salary certificate or income proof',
                    'Tenancy contract (Ejari or equivalent)',
                ],
                'documents' => [
                    'Sponsor Emirates ID copy',
                    'Sponsor passport copy with visa page',
                    'Family member passport (original)',
                    'Attested marriage/birth certificates',
                    'Sponsor employment contract',
                    'Bank statements (3 months)',
                    'Tenancy contract',
                    'Medical fitness certificate (upon arrival)',
                ],
                'process_steps' => [
                    'Submit sponsor documents and family member details',
                    'Receive entry permit approval',
                    'Family member enters UAE on entry permit',
                    'Complete medical fitness test',
                    'Emirates ID biometric enrollment',
                    'Visa stamping in passport',
                    'Receive residency card',
                ],
                'processing_time' => '7-14 working days',
                'price_range' => 'AED 1,500 - 3,000',
                'cta_text' => 'Apply Now',
                'cta_link' => '/typing/contact',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Residency Renewal',
                'slug' => 'residency-renewal',
                'short_description' => 'Renew existing family residency permits before expiry to avoid penalties.',
                'long_description' => '<p>Renew existing family residency permits before expiry. We handle renewal applications, coordinate required medical checks and ensure timely Emirates ID updates.</p><p>Avoid overstay fines and immigration penalties by renewing on time with our proactive reminder and fast-track services.</p>',
                'requirements' => [
                    'Valid sponsor residency visa',
                    'Current family member residency (within renewal window)',
                    'Updated medical fitness results',
                    'Active health insurance',
                    'Valid tenancy contract',
                ],
                'documents' => [
                    'Family member passport (original)',
                    'Current residency visa page',
                    'Sponsor Emirates ID',
                    'Medical fitness certificate',
                    'Health insurance certificate',
                    'Updated tenancy contract (Ejari)',
                ],
                'process_steps' => [
                    'Submit renewal application',
                    'Complete medical fitness test',
                    'Update health insurance',
                    'Emirates ID renewal/update',
                    'New visa stamping',
                ],
                'processing_time' => '5-10 working days',
                'price_range' => 'AED 1,200 - 2,500',
                'cta_text' => 'Renew Now',
                'cta_link' => '/typing/contact',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'New Born Baby',
                'slug' => 'new-born-baby',
                'short_description' => 'Register and sponsor newborns with required birth documentation and medical certificates.',
                'long_description' => '<p>Register and sponsor newborns with required birth documentation and medical certificates. We assist with hospital paperwork, birth certificates and adding the newborn to the family residency visa.</p><p>Our team ensures seamless coordination between hospitals, embassies (if needed) and immigration authorities.</p>',
                'requirements' => [
                    'Parent residency visas (both valid)',
                    'Hospital birth certificate',
                    'Marriage certificate (attested)',
                    'Parent passports',
                    'Newborn passport (from home country embassy)',
                ],
                'documents' => [
                    'Hospital birth notification',
                    'Birth certificate from municipality',
                    'Attested marriage certificate',
                    'Parent passports and Emirates IDs',
                    'Newborn passport',
                    'Parent visa pages',
                ],
                'process_steps' => [
                    'Obtain hospital birth certificate',
                    'Register birth with municipality',
                    'Apply for newborn passport at embassy',
                    'Submit residency application for newborn',
                    'Medical checkup if required',
                    'Emirates ID enrollment',
                    'Visa stamping',
                ],
                'processing_time' => '10-21 working days',
                'price_range' => 'AED 1,500 - 3,500',
                'cta_text' => 'Register Baby',
                'cta_link' => '/typing/contact',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Cancellation',
                'slug' => 'cancellation',
                'short_description' => 'Assist with visa cancellations and related clearance if the sponsor requests it.',
                'long_description' => '<p>Assist with visa cancellations and related clearance if the sponsor requests it. We manage exit formalities, clearance letters and coordination with authorities to close cases quickly.</p><p>Whether for departure, status change, or other reasons, we ensure proper cancellation to avoid future complications.</p>',
                'requirements' => [
                    'Family member current residency visa',
                    'Reason for cancellation',
                    'No outstanding fines or violations',
                    'Sponsor consent (if applicable)',
                ],
                'documents' => [
                    'Family member passport (original)',
                    'Current visa page',
                    'Emirates ID',
                    'Sponsor Emirates ID (if required)',
                    'Cancellation request letter',
                ],
                'process_steps' => [
                    'Submit cancellation application',
                    'Return Emirates ID card',
                    'Receive cancellation stamp',
                    'Obtain exit permit if required',
                    'Complete departure within grace period',
                ],
                'processing_time' => '2-5 working days',
                'price_range' => 'AED 500 - 1,200',
                'cta_text' => 'Start Cancellation',
                'cta_link' => '/typing/contact',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        // Create emirates and their visa types
        foreach ($emirates as $emirateData) {
            $emirate = FamilyVisaEmirate::updateOrCreate(
                ['slug' => $emirateData['slug']],
                $emirateData
            );

            // Create visa types for each emirate
            foreach ($visaTypes as $typeData) {
                FamilyVisaType::updateOrCreate(
                    ['emirate_id' => $emirate->id, 'slug' => $typeData['slug']],
                    array_merge($typeData, ['emirate_id' => $emirate->id])
                );
            }
        }
    }

    /**
     * Seed typing section settings.
     */
    protected function seedTypingSettings(): void
    {
        $settings = [
            // Company Settings
            ['key' => 'company_name', 'value' => 'SS Group Travels & Typing', 'section' => 'typing'],
            ['key' => 'company_tagline', 'value' => 'Centre for all your documentation services', 'section' => 'typing'],
            ['key' => 'company_email', 'value' => 'typing@ssgroupuae.com', 'section' => 'typing'],
            ['key' => 'company_phone', 'value' => '+971 4 123 4567', 'section' => 'typing'],
            ['key' => 'company_whatsapp', 'value' => '+971 50 123 4567', 'section' => 'typing'],
            ['key' => 'company_address', 'value' => 'Bur Dubai, Dubai, UAE', 'section' => 'typing'],
            ['key' => 'company_description', 'value' => 'We believe in team work, discipline, innovation, customer satisfaction and most importantly in respecting everyone. We are fortunate to have a disciplined team that work towards providing the best for our customers. Our team work together to provide fruitful results to our customers and make them continue their business relationship with us.', 'section' => 'typing'],
            ['key' => 'working_hours', 'value' => 'Mon-Sat: 9:00 AM - 6:00 PM', 'section' => 'typing'],
            ['key' => 'hero_image', 'value' => 'settings/hero/kabah_1768984201_PGeKumrh.webp', 'section' => 'typing'],

            // Mission/Vision/Values
            ['key' => 'company_mission', 'value' => 'We simplify government documentation with exceptional service, ensuring a seamless process for UAE residents. Our commitment to excellence helps clients save time and move forward with confidence.', 'section' => 'typing'],
            ['key' => 'company_vision', 'value' => 'Our vision is to exceed expectations with outstanding service and minimal client effort. With an efficient team, we foster lasting relationships built on trust and results.', 'section' => 'typing'],
            ['key' => 'company_values', 'value' => 'Teamwork, discipline, and customer satisfaction drive us to deliver exceptional results. We go the extra mile to build enduring partnerships and ensure client success.', 'section' => 'typing'],

            // SEO Settings
            ['key' => 'meta_title', 'value' => 'SS Group Typing Services | Professional Documentation Services UAE', 'section' => 'typing'],
            ['key' => 'meta_description', 'value' => 'Professional typing and documentation services in Dubai. Visa processing, Emirates ID, labour services, attestation, and more. Fast & reliable service.', 'section' => 'typing'],
            ['key' => 'meta_keywords', 'value' => 'typing services dubai, visa services uae, emirates id, labour card, tasheel, attestation, PRO services dubai', 'section' => 'typing'],

            // Social Media Settings
            ['key' => 'social_facebook', 'value' => '', 'section' => 'typing'],
            ['key' => 'social_instagram', 'value' => '', 'section' => 'typing'],
            ['key' => 'social_twitter', 'value' => '', 'section' => 'typing'],
            ['key' => 'social_linkedin', 'value' => '', 'section' => 'typing'],
            ['key' => 'social_youtube', 'value' => '', 'section' => 'typing'],

            // Contact Settings
            ['key' => 'contact_email', 'value' => 'info@ssgroupuae.com', 'section' => 'typing'],
            ['key' => 'contact_phone', 'value' => '+971 4 123 4567', 'section' => 'typing'],
            ['key' => 'contact_description', 'value' => 'Have questions about our typing services? Get in touch with our team for quick assistance.', 'section' => 'typing'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key'], 'section' => $setting['section']],
                $setting
            );
        }
    }
}
