# Software Requirements Specification (SRS)
# Dubai Travel & Services - Admin Panel

**Document Version:** 2.0  
**Date:** January 14, 2026  
**Project:** Dubai Travel & Services Platform  
**Module:** Hajj & Umrah Admin Panel  
**Status:** Approved for Development

---

## Document Control

| Version | Date | Author | Description |
|---------|------|--------|-------------|
| 1.0 | Jan 14, 2026 | Development Team | Initial draft |
| 2.0 | Jan 14, 2026 | Development Team | Updated for Blade/Alpine.js stack |

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Overall Description](#2-overall-description)
3. [System Architecture](#3-system-architecture)
4. [Functional Requirements](#4-functional-requirements)
5. [Non-Functional Requirements](#5-non-functional-requirements)
6. [User Interface Requirements](#6-user-interface-requirements)
7. [Data Requirements](#7-data-requirements)
8. [External Interface Requirements](#8-external-interface-requirements)
9. [Security Requirements](#9-security-requirements)
10. [Quality Attributes](#10-quality-attributes)
11. [Constraints](#11-constraints)
12. [Appendices](#12-appendices)

---

## 1. Introduction

### 1.1 Purpose

This Software Requirements Specification (SRS) document provides a complete description of the software requirements for the Dubai Travel & Services Admin Panel, specifically focusing on the Hajj & Umrah section. This document serves as a contractual agreement between stakeholders and the development team, ensuring all parties have a clear understanding of the system's capabilities, constraints, and expected behavior.

### 1.2 Scope

The Admin Panel is a web-based content management system designed to manage the public-facing Hajj & Umrah travel services website. The system will enable authorized administrators to:

- Manage travel packages (Hajj & Umrah offerings)
- Create and publish blog articles and content
- Maintain team member profiles
- Handle customer testimonials
- Process and respond to customer inquiries
- Configure site-wide settings and appearance

**Future Expansion:**
- Tour & Travel Module (Phase 2)
- Typing Services Module (Phase 3)

### 1.3 Definitions, Acronyms, and Abbreviations

| Term | Definition |
|------|------------|
| Admin Panel | The backend management interface for administrators |
| CMS | Content Management System |
| CRUD | Create, Read, Update, Delete operations |
| SPA | Single Page Application |
| UI/UX | User Interface / User Experience |
| WCAG | Web Content Accessibility Guidelines |
| SEO | Search Engine Optimization |
| RBAC | Role-Based Access Control |

### 1.4 References

- Laravel 12 Documentation
- Tailwind CSS v4 Documentation
- Alpine.js v3 Documentation
- IEEE 830-1998 SRS Standard
- WCAG 2.1 Accessibility Guidelines

### 1.5 Document Overview

This document is organized into sections covering the overall system description, detailed functional requirements, non-functional requirements, interface specifications, and design constraints. Each section is intended to provide comprehensive information for stakeholders, designers, and developers.

---

## 2. Overall Description

### 2.1 Product Perspective

The Admin Panel is a subsystem of the larger Dubai Travel & Services platform. It interfaces with:

- **Public Website**: Vue.js frontend displaying packages, articles, and content
- **Database**: MySQL database storing all content and configuration
- **File Storage**: Local/Cloud storage for media assets
- **Email System**: For inquiry notifications and responses

### 2.2 Product Functions

The system shall provide the following high-level functions:

| Function Category | Description |
|-------------------|-------------|
| Authentication | Secure admin login with session management |
| Dashboard | Overview statistics and quick actions |
| Package Management | Full CRUD for Hajj/Umrah travel packages |
| Content Management | Article creation with rich text editing |
| Team Management | Staff profiles and ordering |
| Testimonial Management | Customer reviews with approval workflow |
| Inquiry Management | Contact form submissions handling |
| Settings Management | Site configuration and appearance |

### 2.3 User Classes and Characteristics

#### 2.3.1 Role Hierarchy

| User Class | Description | Technical Proficiency | Access Level |
|------------|-------------|----------------------|---------------|
| Super Admin | Full system access, manages all modules, assigns sections to admins | Moderate | All Sections |
| Admin | Section-specific access, assigned by Super Admin | Basic to Moderate | Assigned Section Only |
| User | Registered customer, can purchase packages and track orders | Basic | User Dashboard Only |

#### 2.3.2 Role Capabilities

**Super Admin:**
- Access to all three sections (Hajj & Umrah, Tour & Travel, Typing Services)
- Create and manage Admin accounts
- Assign specific sections to Admin users
- View all reports and analytics
- Manage site-wide settings
- Full CRUD on all content

**Admin:**
- Access only to assigned section(s)
- Full CRUD within assigned section
- View section-specific reports
- Manage section settings
- Cannot access other sections
- Cannot manage other admins

**User (Customer):**
- Register/Login to account
- Browse and purchase packages
- Track package purchase status
- View purchase history
- Update profile information
- Receive notifications about bookings

### 2.4 Operating Environment

| Component | Requirement |
|-----------|-------------|
| Server OS | Linux (Ubuntu 22.04+) or Windows Server |
| Web Server | Nginx or Apache |
| PHP Version | 8.2 or higher |
| Database | MySQL 8.0 or higher |
| Browser Support | Chrome 90+, Firefox 88+, Safari 14+, Edge 90+ |
| Screen Resolution | Minimum 320px width (fully responsive) |

### 2.5 Design and Implementation Constraints

1. The admin panel must use Laravel Blade templates with Alpine.js for interactivity
2. Styling must use Tailwind CSS v4 with custom design tokens
3. Backend must follow the Service Pattern architecture
4. All database operations must go through Eloquent ORM
5. File uploads limited to 10MB per file
6. Session timeout after 2 hours of inactivity

### 2.6 Assumptions and Dependencies

**Assumptions:**
- Users have stable internet connection (minimum 1 Mbps)
- Users have basic computer literacy
- Modern web browser is available
- JavaScript is enabled in the browser

**Dependencies:**
- MySQL database availability
- File storage accessibility
- Mail server for notifications (optional)

---

## 3. System Architecture

### 3.1 Architecture Overview

The system follows a layered architecture pattern with clear separation of concerns:

| Layer | Responsibility |
|-------|----------------|
| Presentation | Blade views with Alpine.js interactions |
| Controller | Request handling and response formatting |
| Service | Business logic and data orchestration |
| Repository | Data access abstraction (optional) |
| Model | Database entity representation |

### 3.2 Technology Stack

| Layer | Technology | Purpose |
|-------|------------|---------|
| Frontend | Laravel Blade | Server-side templating |
| Interactivity | Alpine.js 3.x | Reactive UI components |
| Styling | Tailwind CSS v4 | Utility-first CSS framework |
| Icons | Lucide Icons | Consistent iconography |
| Backend | Laravel 12 | PHP framework |
| Database | MySQL 8.x | Relational data storage |
| Authentication | Laravel Fortify | User authentication |
| Storage | Laravel Storage | File management |

### 3.3 Service Pattern Architecture

All business logic shall be encapsulated in Service classes:

| Service | Responsibility |
|---------|----------------|
| PackageService | Package CRUD, duplication, status management |
| ArticleService | Article CRUD, publishing workflow |
| TeamService | Team member management, ordering |
| TestimonialService | Testimonial CRUD, approval workflow |
| InquiryService | Inquiry handling, status updates |
| SettingService | Configuration management |
| MediaService | File upload, image processing |
| SlugService | URL slug generation |

### 3.4 Directory Structure

```
app/
├── Http/
│   ├── Controllers/Admin/
│   ├── Middleware/
│   └── Requests/Admin/
├── Models/
├── Services/
├── Enums/
└── Helpers/

resources/
├── views/
│   └── admin/
│       ├── layouts/
│       ├── components/
│       └── pages/
└── css/
    └── admin.css

routes/
└── admin.php
```

---

## 4. Functional Requirements

### 4.1 Authentication Module (AUTH)

#### AUTH-001: Admin Login Access
**Priority:** Critical  
**Description:** The system shall provide a login link in the public website footer that directs users to the admin authentication page.

**Acceptance Criteria:**
- Login link visible in footer with label "Admin Login"
- Login page accessible at `/admin/login`
- Login form requires email and password
- "Remember Me" option available
- Forgot password functionality provided

#### AUTH-002: Session Management
**Priority:** Critical  
**Description:** The system shall maintain secure user sessions with automatic timeout.

**Acceptance Criteria:**
- Sessions expire after 2 hours of inactivity
- Users redirected to login after session expiry
- Concurrent session handling configurable
- Secure session storage

#### AUTH-003: Role-Based Access Control
**Priority:** Critical  
**Description:** The system shall implement role-based access control with section assignment.

**Acceptance Criteria:**
- Three user roles: Super Admin, Admin, User
- Super Admin can assign sections to Admin users
- Admin users only see assigned section(s) in sidebar
- Permission checking on all protected routes
- Unauthorized access redirects with appropriate message

#### AUTH-004: User Registration
**Priority:** High  
**Description:** The system shall allow customers to register for package purchases.

**Acceptance Criteria:**
- Registration form with: Name, Email, Phone, Password
- Email verification required
- Optional: Social login (Google, Facebook)
- Profile completion after registration
- Terms and conditions acceptance

#### AUTH-005: User Login
**Priority:** High  
**Description:** The system shall provide secure customer login.

**Acceptance Criteria:**
- Login via email and password
- "Remember Me" functionality
- Password reset via email
- Account lockout after 5 failed attempts
- Redirect to intended page after login

#### AUTH-006: Admin Section Assignment
**Priority:** High  
**Description:** Super Admin can assign sections to Admin users.

**Acceptance Criteria:**
- Section options: Hajj & Umrah, Tour & Travel, Typing Services
- Admin can be assigned one or multiple sections
- Assignment reflected immediately in sidebar
- Revocation removes access immediately
- Audit log of assignment changes

### 4.2 Dashboard Module (DASH)

#### DASH-001: Statistics Overview
**Priority:** High  
**Description:** The dashboard shall display key performance indicators and statistics.

**Metrics Required:**
| Metric | Description |
|--------|-------------|
| Total Packages | Count with active/inactive breakdown |
| Total Articles | Count with published/draft breakdown |
| New Inquiries | Unread inquiry count |
| Total Testimonials | Count with approved/pending breakdown |

**Acceptance Criteria:**
- Statistics update on page load
- Visual indicators for pending items
- Click-through to respective management pages

#### DASH-002: Recent Activity
**Priority:** Medium  
**Description:** Display timeline of recent administrative actions.

**Acceptance Criteria:**
- Show last 10 activities
- Include timestamp and action type
- Include actor (admin user) name
- Filter by activity type

#### DASH-003: Quick Actions
**Priority:** Medium  
**Description:** Provide shortcut buttons for common tasks.

**Actions Required:**
- Add New Package
- Write New Article
- View Inquiries
- View Pending Testimonials

### 4.3 Package Management Module (PKG)

#### PKG-001: Package Listing
**Priority:** Critical  
**Description:** Display all packages in a sortable, filterable table format.

**Table Columns:**
| Column | Sortable | Filterable |
|--------|----------|------------|
| Image Thumbnail | No | No |
| Title | Yes | Search |
| Type | Yes | Dropdown |
| Price | Yes | Range |
| Duration | Yes | No |
| Featured | No | Toggle |
| Status | Yes | Dropdown |
| Actions | No | No |

**Features Required:**
- Pagination (10, 25, 50 per page)
- Bulk selection with actions
- Quick status toggle
- Quick featured toggle

#### PKG-002: Package Creation
**Priority:** Critical  
**Description:** Provide comprehensive form for creating new packages.

**Form Sections:**
1. **Basic Information**
   - Title (required, max 200 characters)
   - Slug (auto-generated, editable)
   - Type (Hajj/Umrah selection)
   - Price (required, numeric)
   - Currency (USD, AED, SAR)
   - Duration in days (required)

2. **Media**
   - Main image upload (required)
   - Gallery images (optional, max 10)
   - Image preview functionality

3. **Features**
   - Dynamic list management
   - Add/remove capabilities
   - Drag-to-reorder

4. **Description**
   - Rich text editor
   - Image embedding support

5. **Itinerary**
   - Day-by-day planning
   - Activities per day
   - Dynamic day addition

6. **Hotel Information**
   - Hotel name
   - Star rating
   - Location details
   - Amenities list

7. **Inclusions/Exclusions**
   - Two separate lists
   - Dynamic item management

8. **Availability**
   - Departure dates
   - Maximum capacity

9. **Status**
   - Active/Inactive toggle
   - Featured toggle

#### PKG-003: Package Editing
**Priority:** Critical  
**Description:** Allow modification of existing packages.

**Acceptance Criteria:**
- All create form fields editable
- Current image preview shown
- Change history logged
- Save and Continue option
- Save and Exit option

#### PKG-004: Package Deletion
**Priority:** High  
**Description:** Soft delete packages with recovery option.

**Acceptance Criteria:**
- Confirmation modal required
- Soft delete implementation
- 30-day recovery window
- Associated data handling

#### PKG-005: Package Duplication
**Priority:** Medium  
**Description:** Create copy of existing package.

**Acceptance Criteria:**
- New title with "Copy of" prefix
- New unique slug generated
- Status set to inactive
- Redirect to edit page

### 4.4 Article Management Module (ART)

#### ART-001: Article Listing
**Priority:** Critical  
**Description:** Display articles with filtering and sorting.

**Table Columns:**
| Column | Sortable | Filterable |
|--------|----------|------------|
| Thumbnail | No | No |
| Title | Yes | Search |
| Category | Yes | Dropdown |
| Status | Yes | Dropdown |
| Published Date | Yes | Date Range |
| Views | Yes | No |
| Actions | No | No |

#### ART-002: Article Creation
**Priority:** Critical  
**Description:** Full-featured article editor.

**Form Sections:**
1. **Content**
   - Title (required)
   - Slug (auto-generated)
   - Excerpt (max 500 characters)
   - Content (rich text editor)

2. **Categorization**
   - Category selection
   - Inline category creation
   - Tags input

3. **Media**
   - Featured image
   - In-content image upload

4. **SEO**
   - Meta title
   - Meta description
   - SEO preview

5. **Publishing**
   - Save as Draft
   - Publish immediately
   - Schedule for future

#### ART-003: Article Publishing Workflow
**Priority:** High  
**Description:** Draft to published state management.

**Acceptance Criteria:**
- Draft saved without publishing
- Publish action updates status and date
- Unpublish returns to draft
- Scheduled publishing support

#### ART-004: Category Management
**Priority:** High  
**Description:** Manage article categories.

**Acceptance Criteria:**
- CRUD operations for categories
- Inline creation from article form
- Category article count display
- Delete protection if articles exist

### 4.5 Team Management Module (TEAM)

#### TEAM-001: Team Listing
**Priority:** High  
**Description:** Display team members in grid or list format.

**Display Options:**
- Grid view with cards
- List view with table
- Drag-to-reorder functionality

**Card Information:**
- Photo
- Name
- Role/Position
- Status badge
- Quick actions

#### TEAM-002: Team Member Management
**Priority:** High  
**Description:** Add and edit team members.

**Form Fields:**
| Field | Required | Type |
|-------|----------|------|
| Name | Yes | Text |
| Role/Position | Yes | Text |
| Photo | No | Image Upload |
| Bio | No | Textarea |
| Email | No | Email |
| Phone | No | Text |
| Facebook | No | URL |
| Twitter | No | URL |
| LinkedIn | No | URL |
| Instagram | No | URL |
| Display Order | Yes | Number |
| Is Active | Yes | Toggle |

#### TEAM-003: Team Ordering
**Priority:** Medium  
**Description:** Reorder team members display sequence.

**Acceptance Criteria:**
- Drag and drop interface
- Order persists to database
- Batch order update

### 4.6 Testimonial Management Module (TEST)

#### TEST-001: Testimonial Listing
**Priority:** High  
**Description:** Display testimonials with approval workflow.

**Table Columns:**
| Column | Description |
|--------|-------------|
| Avatar | Customer photo |
| Name | Customer name |
| Rating | Star rating (1-5) |
| Content Preview | Truncated text |
| Package | Associated package |
| Featured | Featured status |
| Status | Approved/Pending |
| Actions | Approve/Reject/Edit/Delete |

#### TEST-002: Testimonial Form
**Priority:** High  
**Description:** Create and edit testimonials.

**Form Fields:**
| Field | Required | Type |
|-------|----------|------|
| Name | Yes | Text |
| Location | No | Text |
| Avatar | No | Image Upload |
| Rating | Yes | Star Selection (1-5) |
| Content | Yes | Textarea |
| Package | No | Dropdown |
| Is Featured | No | Toggle |
| Is Approved | No | Toggle |

#### TEST-003: Approval Workflow
**Priority:** High  
**Description:** Quick approve/reject testimonials.

**Acceptance Criteria:**
- One-click approve action
- One-click reject action
- Batch approval option
- Status change logging

### 4.7 Inquiry Management Module (INQ)

#### INQ-001: Inquiry Listing
**Priority:** High  
**Description:** Display customer inquiries with status tracking.

**Table Columns:**
| Column | Description |
|--------|-------------|
| Name | Customer name |
| Email | Contact email |
| Subject | Inquiry subject |
| Package | Associated package |
| Status | New/Read/Responded/Closed |
| Date | Submission date |
| Actions | View/Delete |

**Status Indicators:**
| Status | Color | Description |
|--------|-------|-------------|
| New | Blue | Unread inquiry |
| Read | Yellow | Viewed by admin |
| Responded | Green | Reply sent |
| Closed | Gray | Resolved |

#### INQ-002: Inquiry Detail View
**Priority:** High  
**Description:** Full inquiry information display.

**Information Displayed:**
- Customer details (name, email, phone)
- Subject and full message
- Associated package (if any)
- Submission timestamp
- Current status

**Actions Available:**
- Mark as Read
- Mark as Responded
- Add Admin Notes
- Reply via Email
- Close Inquiry
- Delete

#### INQ-003: Inquiry Status Management
**Priority:** Medium  
**Description:** Status progression tracking.

**Acceptance Criteria:**
- Status change with timestamp
- Admin notes per status change
- Status history maintained
- Bulk status updates

### 4.8 Settings Management Module (SET)

#### SET-001: General Settings
**Priority:** High  
**Description:** Core site configuration.

**Settings:**
| Setting | Type | Description |
|---------|------|-------------|
| Company Name | Text | Business name |
| Logo | Image | Site logo |
| Email | Email | Contact email |
| Phone | Text | Contact phone |
| Address | Textarea | Office address |

#### SET-002: Social Media Settings
**Priority:** Medium  
**Description:** Social platform links.

**Settings:**
| Platform | Type |
|----------|------|
| Facebook | URL |
| Twitter | URL |
| Instagram | URL |
| LinkedIn | URL |
| YouTube | URL |
| WhatsApp | Text |

#### SET-003: SEO Settings
**Priority:** Medium  
**Description:** Default SEO configuration.

**Settings:**
| Setting | Type | Max Length |
|---------|------|------------|
| Meta Title | Text | 60 chars |
| Meta Description | Textarea | 160 chars |
| Meta Keywords | Text | 255 chars |
| Google Analytics ID | Text | 50 chars |

#### SET-004: Appearance Settings
**Priority:** Medium  
**Description:** Visual customization.

**Settings:**
| Setting | Type |
|---------|------|
| Primary Color | Color Picker |
| Hero Banner | Image Upload |
| Hero Title | Text |
| Hero Subtitle | Text |

#### SET-005: Office Locations
**Priority:** Medium  
**Description:** Multiple office management.

**Per Location Fields:**
| Field | Required | Type |
|-------|----------|------|
| Name | Yes | Text |
| Address | Yes | Textarea |
| Phone | No | Text |
| Email | No | Email |
| Map Coordinates | No | Lat/Lng |
| Is Active | Yes | Toggle |

### 4.9 User Dashboard Module (USER)

#### USER-001: User Dashboard
**Priority:** High  
**Description:** Registered users can view their personalized dashboard.

**Dashboard Elements:**
| Element | Description |
|---------|-------------|
| Welcome Message | Personalized greeting with user name |
| Active Bookings | Current packages with status |
| Booking History | Past completed packages |
| Quick Actions | View packages, Update profile |
| Notifications | Recent updates about bookings |

#### USER-002: Package Purchase
**Priority:** Critical  
**Description:** Users can purchase packages and track status.

**Purchase Flow:**
1. Browse packages on public website
2. Select package and click "Book Now"
3. Login/Register if not authenticated
4. Fill booking details (travelers, dates, preferences)
5. Review booking summary
6. Submit booking request
7. Receive confirmation email
8. Track status in dashboard

**Booking Statuses:**
| Status | Description | Color |
|--------|-------------|-------|
| Pending | Awaiting admin confirmation | Yellow |
| Confirmed | Booking accepted, payment pending | Blue |
| Paid | Payment received | Green |
| Processing | Documents/Visa in progress | Purple |
| Ready | All preparations complete | Teal |
| Completed | Travel completed | Gray |
| Cancelled | Booking cancelled | Red |

#### USER-003: Booking Details
**Priority:** High  
**Description:** Users can view detailed information about each booking.

**Information Displayed:**
- Package name and details
- Booking date and status
- Traveler information
- Payment status and history
- Important dates (departure, return)
- Documents checklist
- Admin notes/messages
- Contact support option

#### USER-004: User Profile Management
**Priority:** High  
**Description:** Users can manage their profile information.

**Profile Fields:**
| Field | Required | Editable |
|-------|----------|----------|
| Full Name | Yes | Yes |
| Email | Yes | No (verified) |
| Phone | Yes | Yes |
| Date of Birth | No | Yes |
| Passport Number | No | Yes |
| Passport Expiry | No | Yes |
| Address | No | Yes |
| Emergency Contact | No | Yes |
| Profile Photo | No | Yes |

#### USER-005: Booking History
**Priority:** Medium  
**Description:** Users can view all past bookings.

**History Features:**
- List of all past bookings
- Filter by year, status, package type
- View booking details
- Download booking confirmation PDF
- Re-book same package option

### 4.10 Admin Management Module (ADMIN)

#### ADMIN-001: Admin User Listing
**Priority:** High  
**Description:** Super Admin can view and manage all admin users.

**Table Columns:**
| Column | Description |
|--------|-------------|
| Name | Admin full name |
| Email | Login email |
| Role | Super Admin / Admin |
| Assigned Sections | List of accessible sections |
| Status | Active / Inactive |
| Last Login | Date and time |
| Actions | Edit, Deactivate, Delete |

#### ADMIN-002: Admin Creation
**Priority:** High  
**Description:** Super Admin can create new admin accounts.

**Form Fields:**
| Field | Required | Type |
|-------|----------|------|
| Name | Yes | Text |
| Email | Yes | Email (unique) |
| Password | Yes | Password |
| Role | Yes | Select (Super Admin/Admin) |
| Sections | Required for Admin | Multi-select checkboxes |
| Is Active | Yes | Toggle |

#### ADMIN-003: Section Assignment
**Priority:** High  
**Description:** Assign or modify section access for admin users.

**Available Sections:**
| Section | Code |
|---------|------|
| Hajj & Umrah | hajj |
| Tour & Travel | tour |
| Typing Services | typing |

**Assignment Features:**
- Multi-select for multiple sections
- Immediate effect on assignment
- Visual indicator in admin list
- Audit log of changes

### 4.11 Booking Management Module (BOOK)

#### BOOK-001: Booking Listing
**Priority:** Critical  
**Description:** Admin can view all package bookings.

**Table Columns:**
| Column | Sortable | Filterable |
|--------|----------|------------|
| Booking ID | Yes | Search |
| Customer Name | Yes | Search |
| Package | Yes | Dropdown |
| Status | Yes | Dropdown |
| Booking Date | Yes | Date Range |
| Travel Date | Yes | Date Range |
| Amount | Yes | Range |
| Actions | No | No |

#### BOOK-002: Booking Status Management
**Priority:** Critical  
**Description:** Admin can update booking status.

**Workflow:**
1. View booking details
2. Update status from dropdown
3. Add admin notes (optional)
4. Save changes
5. User receives notification
6. Status history logged

#### BOOK-003: Booking Communication
**Priority:** High  
**Description:** Admin can communicate with customers about bookings.

**Features:**
- Send message to customer
- View message history
- Attach documents
- Email notification to customer
- Message templates

---

## 5. Non-Functional Requirements

### 5.1 Performance Requirements

| Metric | Requirement |
|--------|-------------|
| Page Load Time | < 2 seconds initial load |
| API Response Time | < 500ms for standard operations |
| Image Upload | < 5 seconds for 5MB file |
| Database Queries | < 50 queries per page |
| Concurrent Users | Support 50+ simultaneous admins |

### 5.2 Reliability Requirements

| Metric | Requirement |
|--------|-------------|
| Uptime | 99.5% availability |
| Data Loss | Zero tolerance for data loss |
| Backup | Daily automated backups |
| Recovery Time | < 4 hours for full recovery |

### 5.3 Scalability Requirements

- Support growth to 1000+ packages
- Support growth to 5000+ articles
- Support growth to 10000+ inquiries
- Modular architecture for new sections

### 5.4 Maintainability Requirements

- Code follows PSR-12 standards
- Comprehensive inline documentation
- Test coverage minimum 70%
- Version controlled with Git

---

## 6. User Interface Requirements

### 6.1 Design Principles

1. **Professional Aesthetic**: Clean, modern design reflecting premium travel services
2. **Consistency**: Uniform patterns across all modules
3. **Clarity**: Clear visual hierarchy and intuitive navigation
4. **Efficiency**: Minimize clicks for common tasks
5. **Responsiveness**: Seamless experience across all devices

### 6.2 Responsive Breakpoints

| Breakpoint | Width | Layout Adaptation |
|------------|-------|-------------------|
| Mobile | < 640px | Single column, hidden sidebar, hamburger menu |
| Tablet | 640px - 1024px | Collapsed sidebar (icons), 2-column grids |
| Desktop | > 1024px | Full sidebar (260px), multi-column layouts |

### 6.3 Color Palette

| Color | Value | Usage |
|-------|-------|-------|
| Primary | #D3A762 | CTAs, highlights, active states |
| Primary Hover | #c29652 | Button hover states |
| Sidebar Background | #1e293b | Dark navigation |
| Sidebar Text | #e2e8f0 | Navigation text |
| Page Background | #f8fafc | Main content area |
| Surface | #ffffff | Cards, panels, modals |
| Border | #e2e8f0 | Dividers, outlines |
| Text Primary | #1e293b | Headings |
| Text Secondary | #64748b | Body text |
| Text Muted | #94a3b8 | Captions, hints |
| Success | #22c55e | Active, published, success |
| Warning | #f59e0b | Pending, alerts |
| Error | #ef4444 | Errors, delete actions |
| Info | #3b82f6 | Information, new items |

### 6.4 Typography

| Element | Font Family | Size | Weight |
|---------|-------------|------|--------|
| Page Title | Instrument Sans | 24px | 700 |
| Section Title | Instrument Sans | 18px | 600 |
| Body Text | Instrument Sans | 14px | 400 |
| Small Text | Instrument Sans | 12px | 400 |
| Button Text | Instrument Sans | 14px | 600 |
| Table Header | Instrument Sans | 12px | 600 |
| Input Label | Instrument Sans | 14px | 500 |

### 6.5 Animation Guidelines

| Animation | Duration | Easing | Usage |
|-----------|----------|--------|-------|
| Fade In | 200ms | ease-out | Modal, dropdown appear |
| Fade Out | 150ms | ease-in | Element removal |
| Slide In | 300ms | ease-out | Sidebar, panels |
| Slide Out | 200ms | ease-in | Panel close |
| Button Hover | 150ms | ease-in-out | Color transitions |
| Loading Spin | 1000ms | linear | Loading indicators |
| Scale | 100ms | ease-out | Button press feedback |

### 6.6 Layout Structure

```
┌─────────────────────────────────────────────────────────────────┐
│                        HEADER (60px)                            │
│  [☰] [Logo]              [Search]         [Notifications] [User]│
├────────────┬────────────────────────────────────────────────────┤
│            │                                                    │
│  SIDEBAR   │                 MAIN CONTENT                       │
│  (260px)   │                                                    │
│            │  ┌──────────────────────────────────────────────┐  │
│  Dashboard │  │  Breadcrumb: Home > Packages                 │  │
│            │  ├──────────────────────────────────────────────┤  │
│  ━━━━━━━━  │  │                                              │  │
│            │  │  Page Title                    [+ Add New]   │  │
│  HAJJ      │  │                                              │  │
│  Packages  │  │  ┌─────────────────────────────────────────┐ │  │
│  Articles  │  │  │         Content Area                    │ │  │
│  Team      │  │  │                                         │ │  │
│  Testim... │  │  │                                         │ │  │
│  Inquiries │  │  └─────────────────────────────────────────┘ │  │
│  Settings  │  │                                              │  │
│            │  └──────────────────────────────────────────────┘  │
│  ━━━━━━━━  │                                                    │
│            │                                                    │
│  TOUR      │                                                    │
│  (Coming)  │                                                    │
│            │                                                    │
└────────────┴────────────────────────────────────────────────────┘
```

### 6.7 Component Standards

#### Buttons
| Variant | Background | Text | Border | Usage |
|---------|------------|------|--------|-------|
| Primary | #D3A762 | White | None | Main actions |
| Secondary | White | #1e293b | #e2e8f0 | Secondary actions |
| Danger | #ef4444 | White | None | Delete actions |
| Ghost | Transparent | #64748b | None | Tertiary actions |

#### Form Inputs
| State | Border | Background | Shadow |
|-------|--------|------------|--------|
| Default | #e2e8f0 | White | None |
| Focus | #D3A762 | White | Ring |
| Error | #ef4444 | #fef2f2 | None |
| Disabled | #e2e8f0 | #f8fafc | None |

#### Cards
- Background: White
- Border radius: 8px
- Shadow: sm (small shadow)
- Padding: 24px

#### Tables
- Header background: #f8fafc
- Row hover: #f1f5f9
- Border: 1px solid #e2e8f0
- Padding: 12px 16px

---

## 7. Data Requirements

### 7.1 Data Entities

| Entity | Description | Estimated Volume |
|--------|-------------|------------------|
| User | System users (admins & customers) | 100-5000 records |
| Admin Section | Section assignment for admins | 50-100 records |
| Package | Travel package details | 100-500 records |
| Package Gallery | Package images | 500-2500 records |
| Booking | Customer package purchases | 500-10000 records |
| Booking Traveler | Travelers per booking | 1000-25000 records |
| Booking Status Log | Booking status history | 2000-50000 records |
| Article | Blog posts | 200-1000 records |
| Article Category | Article groupings | 10-50 records |
| Team Member | Staff profiles | 10-50 records |
| Testimonial | Customer reviews | 100-500 records |
| Contact Inquiry | Customer messages | 1000-10000 records |
| Site Setting | Configuration | 50-100 records |
| Office Location | Office details | 5-20 records |
| FAQ | Frequently asked questions | 50-200 records |

### 7.2 Complete Database Schema

#### 7.2.1 Entity Relationship Diagram (Text Representation)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                            DATABASE SCHEMA                                   │
└─────────────────────────────────────────────────────────────────────────────┘

                                    ┌─────────────┐
                                    │   users     │
                                    ├─────────────┤
                                    │ PK: id      │
                                    │ name        │
                                    │ email       │
                                    │ role        │
                                    │ ...         │
                                    └──────┬──────┘
                                           │
              ┌────────────────────────────┼────────────────────────────┐
              │                            │                            │
              ▼                            ▼                            ▼
    ┌─────────────────┐          ┌─────────────────┐          ┌─────────────────┐
    │ admin_sections  │          │    bookings     │          │    articles     │
    ├─────────────────┤          ├─────────────────┤          ├─────────────────┤
    │ PK: id          │          │ PK: id          │          │ PK: id          │
    │ FK: user_id     │          │ FK: user_id     │          │ FK: author_id   │
    │ section         │          │ FK: package_id  │          │ FK: category_id │
    └─────────────────┘          │ status          │          └─────────────────┘
                                 └────────┬────────┘
                                          │
                     ┌────────────────────┼────────────────────┐
                     │                    │                    │
                     ▼                    ▼                    ▼
           ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
           │booking_travelers│  │booking_status_  │  │    packages     │
           │                 │  │     logs        │  │                 │
           ├─────────────────┤  ├─────────────────┤  ├─────────────────┤
           │ PK: id          │  │ PK: id          │  │ PK: id          │
           │ FK: booking_id  │  │ FK: booking_id  │  │ type            │
           └─────────────────┘  │ FK: changed_by  │  │ price           │
                                └─────────────────┘  └────────┬────────┘
                                                              │
                                          ┌───────────────────┼───────────────────┐
                                          │                   │                   │
                                          ▼                   ▼                   ▼
                                ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐
                                │ package_gallery │ │  testimonials   │ │contact_inquiries│
                                ├─────────────────┤ ├─────────────────┤ ├─────────────────┤
                                │ PK: id          │ │ PK: id          │ │ PK: id          │
                                │ FK: package_id  │ │ FK: package_id  │ │ FK: package_id  │
                                └─────────────────┘ └─────────────────┘ └─────────────────┘
```

#### 7.2.2 users Table

**Purpose:** Store all system users including Super Admins, Admins, and Customers

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | VARCHAR(255) | NOT NULL | Full name |
| email | VARCHAR(255) | UNIQUE, NOT NULL | Login email |
| email_verified_at | TIMESTAMP | NULL | Email verification date |
| password | VARCHAR(255) | NOT NULL | Hashed password |
| role | ENUM('super_admin', 'admin', 'user') | NOT NULL, DEFAULT 'user' | User role |
| phone | VARCHAR(50) | NULL | Contact phone |
| date_of_birth | DATE | NULL | Customer DOB |
| passport_number | VARCHAR(50) | NULL | Customer passport |
| passport_expiry | DATE | NULL | Passport expiry date |
| address | TEXT | NULL | Full address |
| emergency_contact | VARCHAR(255) | NULL | Emergency contact info |
| profile_photo | VARCHAR(500) | NULL | Profile image path |
| is_active | BOOLEAN | DEFAULT TRUE | Account status |
| last_login_at | TIMESTAMP | NULL | Last login timestamp |
| remember_token | VARCHAR(100) | NULL | Remember me token |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |
| deleted_at | TIMESTAMP | NULL | Soft delete timestamp |

**Indexes:** idx_email, idx_role, idx_is_active

#### 7.2.3 admin_sections Table

**Purpose:** Store section assignments for admin users

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| user_id | BIGINT UNSIGNED | FK → users(id) ON DELETE CASCADE | Admin user |
| section | ENUM('hajj', 'tour', 'typing') | NOT NULL | Assigned section |
| assigned_by | BIGINT UNSIGNED | FK → users(id) ON DELETE SET NULL | Super Admin who assigned |
| assigned_at | TIMESTAMP | NOT NULL | Assignment date |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Unique:** (user_id, section)
**Indexes:** idx_user_id, idx_section

#### 7.2.4 packages Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| title | VARCHAR(200) | NOT NULL | Package name |
| slug | VARCHAR(220) | UNIQUE, NOT NULL | URL-friendly slug |
| type | ENUM('hajj', 'umrah', 'tour') | NOT NULL, DEFAULT 'hajj' | Package type |
| price | DECIMAL(10,2) | NOT NULL | Base price |
| currency | VARCHAR(3) | NOT NULL, DEFAULT 'USD' | Currency code |
| duration_days | INT UNSIGNED | NOT NULL | Trip duration |
| image | VARCHAR(500) | NULL | Featured image path |
| thumbnail | VARCHAR(500) | NULL | Thumbnail image path |
| features | JSON | NULL | Package features list |
| description | TEXT | NULL | Full description |
| inclusions | JSON | NULL | What's included |
| exclusions | JSON | NULL | What's not included |
| itinerary | JSON | NULL | Day-by-day plan |
| hotel_details | JSON | NULL | Hotel information |
| departure_dates | JSON | NULL | Available departure dates |
| max_capacity | INT UNSIGNED | NULL | Maximum travelers |
| current_bookings | INT UNSIGNED | DEFAULT 0 | Current booking count |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured on homepage |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |
| deleted_at | TIMESTAMP | NULL | Soft delete timestamp |

**Indexes:** idx_type, idx_is_active, idx_is_featured, idx_slug

#### 7.2.5 bookings Table

**Purpose:** Store customer package purchases

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| booking_number | VARCHAR(20) | UNIQUE, NOT NULL | Human-readable booking ID (e.g., BK-2026-00001) |
| user_id | BIGINT UNSIGNED | FK → users(id) ON DELETE CASCADE | Customer who booked |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE SET NULL | Booked package |
| status | ENUM('pending', 'confirmed', 'paid', 'processing', 'ready', 'completed', 'cancelled') | NOT NULL, DEFAULT 'pending' | Booking status |
| travel_date | DATE | NULL | Planned departure date |
| return_date | DATE | NULL | Expected return date |
| num_travelers | INT UNSIGNED | NOT NULL, DEFAULT 1 | Number of travelers |
| total_amount | DECIMAL(12,2) | NOT NULL | Total booking amount |
| paid_amount | DECIMAL(12,2) | DEFAULT 0 | Amount paid so far |
| special_requests | TEXT | NULL | Customer special requests |
| admin_notes | TEXT | NULL | Internal admin notes |
| confirmed_at | TIMESTAMP | NULL | Confirmation date |
| paid_at | TIMESTAMP | NULL | Full payment date |
| cancelled_at | TIMESTAMP | NULL | Cancellation date |
| cancellation_reason | TEXT | NULL | Reason for cancellation |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Indexes:** idx_user_id, idx_package_id, idx_status, idx_booking_number, idx_travel_date

#### 7.2.6 booking_travelers Table

**Purpose:** Store individual traveler information for each booking

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| booking_id | BIGINT UNSIGNED | FK → bookings(id) ON DELETE CASCADE | Parent booking |
| full_name | VARCHAR(255) | NOT NULL | Traveler full name |
| passport_number | VARCHAR(50) | NULL | Passport number |
| passport_expiry | DATE | NULL | Passport expiry date |
| date_of_birth | DATE | NULL | Date of birth |
| nationality | VARCHAR(100) | NULL | Nationality |
| gender | ENUM('male', 'female', 'other') | NULL | Gender |
| is_primary | BOOLEAN | DEFAULT FALSE | Primary contact traveler |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Indexes:** idx_booking_id

#### 7.2.7 booking_status_logs Table

**Purpose:** Track booking status changes history

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| booking_id | BIGINT UNSIGNED | FK → bookings(id) ON DELETE CASCADE | Related booking |
| from_status | VARCHAR(50) | NULL | Previous status |
| to_status | VARCHAR(50) | NOT NULL | New status |
| changed_by | BIGINT UNSIGNED | FK → users(id) ON DELETE SET NULL | Admin who changed |
| notes | TEXT | NULL | Notes about change |
| created_at | TIMESTAMP | NULL | Change timestamp |

**Indexes:** idx_booking_id, idx_created_at

#### 7.2.8 package_gallery Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE CASCADE | Parent package |
| image_path | VARCHAR(500) | NOT NULL | Image file path |
| sort_order | INT UNSIGNED | DEFAULT 0 | Display order |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

#### 7.2.9 article_categories Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | VARCHAR(100) | NOT NULL | Category name |
| slug | VARCHAR(120) | UNIQUE, NOT NULL | URL-friendly slug |
| description | TEXT | NULL | Category description |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

#### 7.2.10 articles Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| title | VARCHAR(200) | NOT NULL | Article title |
| slug | VARCHAR(220) | UNIQUE, NOT NULL | URL-friendly slug |
| excerpt | VARCHAR(500) | NULL | Short summary |
| content | LONGTEXT | NULL | Full article content |
| category_id | BIGINT UNSIGNED | FK → article_categories(id) ON DELETE SET NULL | Category |
| author_id | BIGINT UNSIGNED | FK → users(id) ON DELETE CASCADE | Author |
| image | VARCHAR(500) | NULL | Featured image |
| thumbnail | VARCHAR(500) | NULL | Thumbnail image |
| meta_title | VARCHAR(60) | NULL | SEO title |
| meta_description | VARCHAR(160) | NULL | SEO description |
| tags | JSON | NULL | Article tags |
| status | ENUM('draft', 'published') | DEFAULT 'draft' | Publish status |
| published_at | TIMESTAMP | NULL | Publication date |
| views_count | INT UNSIGNED | DEFAULT 0 | View counter |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |
| deleted_at | TIMESTAMP | NULL | Soft delete timestamp |

**Indexes:** idx_status, idx_category_id, idx_author_id, idx_published_at

#### 7.2.11 team_members Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | VARCHAR(100) | NOT NULL | Member name |
| role | VARCHAR(100) | NOT NULL | Position/title |
| image | VARCHAR(500) | NULL | Photo path |
| bio | TEXT | NULL | Biography |
| email | VARCHAR(255) | NULL | Email address |
| phone | VARCHAR(50) | NULL | Phone number |
| social_links | JSON | NULL | Social media links |
| sort_order | INT UNSIGNED | DEFAULT 0 | Display order |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Indexes:** idx_is_active, idx_sort_order

#### 7.2.12 testimonials Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | VARCHAR(100) | NOT NULL | Customer name |
| location | VARCHAR(100) | NULL | Customer location |
| avatar | VARCHAR(500) | NULL | Customer photo |
| rating | TINYINT UNSIGNED | NOT NULL, DEFAULT 5 | Star rating (1-5) |
| content | TEXT | NOT NULL | Testimonial text |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE SET NULL | Related package |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured status |
| is_approved | BOOLEAN | DEFAULT FALSE | Approval status |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Indexes:** idx_is_approved, idx_is_featured, idx_package_id

#### 7.2.13 contact_inquiries Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | VARCHAR(100) | NOT NULL | Customer name |
| email | VARCHAR(255) | NOT NULL | Customer email |
| phone | VARCHAR(50) | NULL | Phone number |
| subject | VARCHAR(200) | NOT NULL | Inquiry subject |
| message | TEXT | NOT NULL | Full message |
| package_id | BIGINT UNSIGNED | FK → packages(id) ON DELETE SET NULL | Related package |
| status | ENUM('new', 'read', 'responded', 'closed') | DEFAULT 'new' | Inquiry status |
| admin_notes | TEXT | NULL | Admin notes |
| responded_at | TIMESTAMP | NULL | Response date |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Indexes:** idx_status, idx_created_at, idx_email

#### 7.2.14 site_settings Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' | Site section |
| key | VARCHAR(100) | NOT NULL | Setting key |
| value | TEXT | NULL | Setting value |
| type | ENUM('string', 'text', 'json', 'boolean', 'integer') | DEFAULT 'string' | Value type |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

**Unique:** (section, key)

#### 7.2.15 office_locations Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' | Site section |
| name | VARCHAR(100) | NOT NULL | Office name |
| address | TEXT | NOT NULL | Full address |
| phone | VARCHAR(50) | NULL | Phone number |
| email | VARCHAR(255) | NULL | Email address |
| map_lat | DECIMAL(10,8) | NULL | Latitude |
| map_lng | DECIMAL(11,8) | NULL | Longitude |
| sort_order | INT UNSIGNED | DEFAULT 0 | Display order |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

#### 7.2.16 faqs Table

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| section | VARCHAR(50) | NOT NULL, DEFAULT 'hajj' | Site section |
| question | VARCHAR(500) | NOT NULL | FAQ question |
| answer | TEXT | NOT NULL | FAQ answer |
| sort_order | INT UNSIGNED | DEFAULT 0 | Display order |
| is_active | BOOLEAN | DEFAULT TRUE | Active status |
| created_at | TIMESTAMP | NULL | Created timestamp |
| updated_at | TIMESTAMP | NULL | Updated timestamp |

### 7.3 Data Retention

| Data Type | Retention Period |
|-----------|------------------|
| Active Content | Indefinite |
| Soft Deleted | 30 days |
| Inquiries | 2 years |
| Booking Records | 7 years (legal requirement) |
| Activity Logs | 1 year |
| Session Data | 24 hours |

### 7.4 Data Validation Rules

| Field Type | Validation |
|------------|------------|
| Email | Valid email format |
| URL | Valid URL format |
| Phone | Numeric with optional formatting |
| Price | Positive decimal, 2 places |
| Slug | Lowercase, alphanumeric, hyphens |
| Image | Max 10MB, jpg/png/webp formats |
| Passport | Alphanumeric, max 20 chars |
| Booking Number | Format: BK-YYYY-XXXXX |

---

## 8. External Interface Requirements

### 8.1 User Interfaces

The admin panel shall provide web-based user interfaces accessible through standard web browsers. All interfaces must be responsive and follow the design specifications in Section 6.

### 8.2 Hardware Interfaces

No direct hardware interfaces required. System operates entirely through web browsers.

### 8.3 Software Interfaces

| Interface | Purpose |
|-----------|---------|
| MySQL | Database operations |
| File System | Media storage |
| SMTP | Email notifications (optional) |
| Browser | User interaction |

### 8.4 Communication Interfaces

| Protocol | Usage |
|----------|-------|
| HTTPS | All web traffic (required) |
| SMTP | Email dispatch (optional) |

---

## 9. Security Requirements

### 9.1 Authentication

- Password minimum 8 characters
- Password complexity requirements
- Session-based authentication
- Secure cookie handling
- CSRF protection on all forms

### 9.2 Authorization

- Role-based access control (RBAC)
- Route-level permission checking
- Action-level permission verification
- Audit logging for sensitive operations

### 9.3 Data Protection

- Input sanitization
- SQL injection prevention (via ORM)
- XSS prevention
- Encrypted passwords (bcrypt)
- HTTPS enforcement

### 9.4 Session Security

- Secure session cookies
- Session regeneration on login
- Automatic timeout (2 hours)
- Single session option (configurable)

---

## 10. Quality Attributes

### 10.1 Usability

- Intuitive navigation structure
- Consistent interaction patterns
- Clear error messages
- Helpful inline documentation
- Keyboard navigation support

### 10.2 Accessibility

- WCAG 2.1 Level AA compliance
- Keyboard-only navigation
- Screen reader compatibility
- Sufficient color contrast
- Focus indicators

### 10.3 Testability

- Unit test coverage > 70%
- Feature test coverage for all CRUD
- Browser testing across supported browsers
- Responsive testing at all breakpoints

---

## 11. Constraints

### 11.1 Technical Constraints

- Must use Laravel 12 framework
- Must use Blade templates for admin views
- Must use Alpine.js for client-side interactivity
- Must use Tailwind CSS v4 for styling
- Must follow Service Pattern architecture
- Must support MySQL 8.0+

### 11.2 Business Constraints

- Development timeline: 3 days for MVP
- Must integrate with existing Vue.js public website
- Must support future Tour and Typing modules
- Budget constraints require open-source solutions

### 11.3 Regulatory Constraints

- GDPR compliance for user data
- Data retention policies
- Privacy policy requirements

---

## Addendum — Codebase Additions (since v2.0)

The following features, data structures, and implementation details are present in the codebase but were not captured in SRS v2.0. They are recorded here to bring the SRS in line with the current implementation (targeting SRS v2.1).

### Summary of additions
- Package gallery support (`package_gallery` table and admin/gallery UI)
- Booking travelers details (`booking_travelers` table and user input validation)
- Booking status audit trail (`booking_status_logs` table and status logging)
- Office locations management (`office_locations` table and admin UI)
- FAQ module with reordering and public display (`faqs` table and `FaqService`)
- User dashboard and bookings UI implemented with Vue + Inertia (routes under `/user`)
- Two-Factor Authentication (2FA) support via Laravel Fortify and settings controllers
- New services and controllers reflecting the above (e.g., `OfficeLocationService`, `FaqService`, `AdminSectionService`, `AdminUserService`, `SiteSettingService`)

---

### A. Package Gallery
**Purpose:** Attach multiple ordered images to a package (public gallery + admin ordering).

**DB Table:** `package_gallery`
**Key fields:**
- `id` (PK)
- `package_id` (FK -> packages)
- `image_path` (string)
- `alt_text` (string, nullable)
- `sort_order` (unsigned int)
- `created_at`, `updated_at`

**Acceptance Criteria:**
- Admin can upload multiple gallery images for a package
- Images are displayed in `sort_order` on public package pages
- Deleting a package removes its gallery items

**References:** migration `2026_01_17_055941_create_package_gallery_table.php`, `PackageGallery` model

---

### B. Booking Travelers
**Purpose:** Store traveler/passenger information per booking.

**DB Table:** `booking_travelers`
**Key fields:**
- `id` (PK)
- `booking_id` (FK -> bookings)
- `full_name` (string)
- `passport_number` (string, nullable)
- `passport_expiry` (date, nullable)
- `date_of_birth` (date, nullable)
- `nationality` (string, nullable)
- `gender` (enum: male, female, other, nullable)
- `is_primary` (boolean)
- `created_at`, `updated_at`

**Acceptance Criteria / Business Rules:**
- Each booking can have one or many travelers
- At least one traveler is required when creating a booking
- Traveler validations enforced on booking submission (name, DOB, optional passport details)

**References:** migration `2026_01_17_055943_create_booking_travelers_table.php`, `BookingController@store`, `BookingService`

---

### C. Booking Status Logs
**Purpose:** Maintain an audit trail of booking status transitions.

**DB Table:** `booking_status_logs`
**Key fields:**
- `id` (PK)
- `booking_id` (FK -> bookings)
- `from_status` (string, nullable)
- `to_status` (string)
- `changed_by` (FK -> users, nullable)
- `notes` (text, nullable)
- `created_at` (timestamp)

**Acceptance Criteria:**
- Status changes are recorded each time a booking status is updated
- Logs include who changed the status (user) and optional notes
- Admin and user views can show status history for a booking

**References:** migration `2026_01_17_055944_create_booking_status_logs_table.php`, `Booking` model relation `statusLogs`

---

### D. Office Locations
**Purpose:** Manage office branches per section (contact addresses, map coords).

**DB Table:** `office_locations`
**Key fields:**
- `id` (PK)
- `section` (enum: hajj, tour, typing, global)
- `name` (string)
- `address` (text)
- `phone` (string, nullable)
- `email` (string, nullable)
- `map_lat`, `map_lng` (decimal, nullable)
- `sort_order` (unsigned int)
- `is_active` (boolean)
- `created_at`, `updated_at`

**Acceptance Criteria:**
- Admin can create/edit/delete office locations and set active/inactive
- Public site can display office locations by section
- Office locations can be ordered via `sort_order`

**References:** migration `2026_01_17_055951_create_office_locations_table.php`, `OfficeLocationService`

---

### E. FAQ Module
**Purpose:** Manage frequently asked questions per section, with admin CRUD and public display.

**DB Table:** `faqs`
**Key behaviors:**
- Reordering of FAQs (for display order)
- Active/Inactive toggles
- Public controllers fetch section-specific FAQs

**Acceptance Criteria:**
- Admin UI provides create/edit/delete and reordering actions
- Public pages display active FAQs for the relevant section (e.g., Hajj)

**References:** migration `2026_01_17_055952_create_faqs_table.php`, `FaqService`, `Admin/Hajj/FaqController`, public controllers using FAQs

---

### F. User Dashboard Implementation (Changed)
**Previous SRS Note:** User Dashboard (Blade + Alpine.js) - NOT YET IMPLEMENTED

**Current Implementation (codebase):**
- Implemented as Vue + Inertia pages under `/user/*` routes (see `routes/web.php`)
- Features include:
  - Dashboard stats (total/pending/confirmed/completed bookings)
  - Bookings listing and filtering (`/user/bookings`)
  - Booking details showing travelers and status logs (`/user/bookings/{id}`)
  - Create booking (public package booking route posts to `User\BookingController@store`)
  - Profile management routes for viewing/updating profile and password

**Acceptance Criteria:**
- Users can authenticate, view dashboard, place bookings (with travelers), and view booking histories
- Dashboard displays accurate stats and recent bookings

**References:** `User\DashboardController`, `User\BookingController`, Inertia pages `resources/js/pages/user/*`, routes prefixed with `user.`

---

### G. Two-Factor Authentication (2FA)
**Purpose:** Optional 2FA for enhanced account security (Fortify integration).

**Behavioral Notes:**
- 2FA is supported via Laravel Fortify
- Controllers and Views exist for enabling/disabling 2FA, regenerating recovery codes, and QR code provisioning
- Settings for profile, password, and 2FA are present under `app/Http/Controllers/Settings/*`

**Acceptance Criteria:**
- Users can enable/disable 2FA from their account settings
- Recovery codes are available and stored securely
- Admin users may be required to use 2FA (configurable)

**References:** `FortifyServiceProvider`, `Settings\TwoFactorAuthenticationController`, `TwoFactorAuthenticatable` support on `User` model

---

### H. Services / Controllers (New or Expanded)
**Notable services/controllers added or expanded in codebase:**
- `AdminSectionService`, `AdminUserService` (admin management and section assignment)
- `FaqService` (FAQ management and reordering)
- `OfficeLocationService` (office CRUD)
- `SiteSettingService` / `SettingService` (site configs and section-specific settings)
- `BookingService` (booking creation with traveler handling and status updates)

**Acceptance Criteria:**
- Services follow the project's Service Pattern and expose `list()`, `getById()`, `create()`, `update()`, `delete()` patterns
- Controllers use services and FormRequests for validation

---

**Revision note:** These additions will be incorporated into SRS v2.1 (date: 2026-01-24) to reflect the current implementation. Any future code changes should be reflected in the SRS via the same addendum mechanism.

---

## 12. Appendices

### Appendix A: Glossary

| Term | Definition |
|------|------------|
| Hajj | Annual Islamic pilgrimage to Mecca |
| Umrah | Lesser pilgrimage that can be undertaken at any time |
| Package | Complete travel offering including flights, accommodation, tours |
| Itinerary | Day-by-day plan of activities |
| Testimonial | Customer review or feedback |

### Appendix B: Related Documents

- Frontend Technical Specification (srs-frontend.md)
- Backend Technical Specification (srs-backend.md)
- Hajj Section Overview (hajj-section-overview.md)
- Development Plan (steps/day-1.md, day-2.md, day-3.md)

### Appendix C: Revision History

| Date | Version | Description | Author |
|------|---------|-------------|--------|
| Jan 14, 2026 | 1.0 | Initial SRS creation | Dev Team |
| Jan 14, 2026 | 2.0 | Stack update to Blade/Alpine.js | Dev Team |

---

## Approval Signatures

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Manager | | | |
| Technical Lead | | | |
| Client Representative | | | |

---

*End of Document*
