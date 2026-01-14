# Hajj Section Overview - Admin Panel Design Analysis

## 1. Current Frontend Structure Analysis

### 1.1 Hajj & Umrah Pages

| File | Purpose | Data Expected |
|------|---------|---------------|
| `hajjhome.vue` | Main landing page for Hajj section | Packages, Features, Blogs, Testimonials |
| `hajjpackage.vue` | Lists Hajj packages | Package objects with id, title, price, image, features[] |
| `umrahpackage.vue` | Lists Umrah packages | Same structure as Hajj packages |
| `articles.vue` | Blog/Article listing | Posts with id, slug, category, title, excerpt, image |
| `article_detail.vue` | Single article view | Slug parameter, full article content |
| `team.vue` | Team members page | Team members with name, role, img, social links |
| `contactus.vue` | Contact form | Form submission: name, email, subject, message |

### 1.2 Hajj Components

| Component | Purpose |
|-----------|---------|
| `hajjheader.vue` | Navigation with email, phone, social links, menu with dropdowns |
| `hajjfooter.vue` | Footer with office info, menus, links |

### 1.3 Layout

| Layout | Purpose |
|--------|---------|
| `HajjUmrahLayout.vue` | Wraps pages with header and footer |

---

## 2. Data Models Required for Admin Panel

### 2.1 Package Data Structure
```typescript
interface Package {
    id: number;
    title: string;
    slug: string;
    type: 'hajj' | 'umrah' | 'tour';
    price: number;
    currency: string;
    duration_days: number;
    image: string;
    thumbnail?: string;
    features: string[];
    description?: string;
    inclusions?: string[];
    exclusions?: string[];
    itinerary?: ItineraryDay[];
    hotel_details?: HotelInfo;
    departure_dates?: Date[];
    max_capacity?: number;
    current_bookings?: number;
    is_featured: boolean;
    is_active: boolean;
    created_at: Date;
    updated_at: Date;
}

interface ItineraryDay {
    day: number;
    title: string;
    description: string;
    activities: string[];
}

interface HotelInfo {
    name: string;
    rating: number;
    location: string;
    distance_to_haram?: string;
    amenities: string[];
}
```

### 2.2 Article/Blog Data Structure
```typescript
interface Article {
    id: number;
    slug: string;
    title: string;
    excerpt: string;
    content: string; // Rich text/HTML
    category: string;
    image: string;
    thumbnail?: string;
    author_id: number;
    author?: User;
    tags?: string[];
    meta_title?: string;
    meta_description?: string;
    is_published: boolean;
    published_at?: Date;
    views_count: number;
    created_at: Date;
    updated_at: Date;
}

interface ArticleCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
}
```

### 2.3 Team Member Data Structure
```typescript
interface TeamMember {
    id: number;
    name: string;
    role: string;
    image: string;
    bio?: string;
    email?: string;
    phone?: string;
    social_links?: {
        facebook?: string;
        twitter?: string;
        linkedin?: string;
        instagram?: string;
    };
    order: number;
    is_active: boolean;
    created_at: Date;
    updated_at: Date;
}
```

### 2.4 Contact/Inquiry Data Structure
```typescript
interface ContactInquiry {
    id: number;
    name: string;
    email: string;
    phone?: string;
    subject: string;
    message: string;
    package_id?: number;
    status: 'new' | 'read' | 'responded' | 'closed';
    admin_notes?: string;
    responded_at?: Date;
    created_at: Date;
    updated_at: Date;
}
```

### 2.5 Testimonial Data Structure
```typescript
interface Testimonial {
    id: number;
    name: string;
    location: string;
    avatar?: string;
    rating: number; // 1-5
    content: string;
    package_id?: number;
    is_featured: boolean;
    is_approved: boolean;
    created_at: Date;
    updated_at: Date;
}
```

### 2.6 Settings/Configuration
```typescript
interface HajjSettings {
    company_name: string;
    company_email: string;
    company_phone: string;
    company_address: string;
    social_links: {
        facebook?: string;
        twitter?: string;
        instagram?: string;
        linkedin?: string;
    };
    office_locations: OfficeLocation[];
    hero_banner_image: string;
    hero_title: string;
    hero_subtitle: string;
}

interface OfficeLocation {
    id: number;
    name: string;
    address: string;
    phone: string;
    email: string;
    map_coordinates?: {
        lat: number;
        lng: number;
    };
}
```

---

## 3. Admin Panel Sections Design

### 3.1 Dashboard
- **Overview Statistics**:
  - Total Packages (Active/Inactive)
  - Total Articles (Published/Draft)
  - New Inquiries (Unread count)
  - Total Team Members
  - Recent Testimonials

- **Quick Actions**:
  - Add New Package
  - Write New Article
  - View New Inquiries

- **Recent Activity Feed**:
  - Latest inquiries
  - Recent article views
  - New testimonials

### 3.2 Packages Management

#### List View
| Column | Actions |
|--------|---------|
| Image Thumbnail | View |
| Title | Edit |
| Type (Hajj/Umrah) | Duplicate |
| Price | Toggle Active |
| Duration | Delete |
| Featured Badge | |
| Status (Active/Inactive) | |

#### Create/Edit Form
- **Basic Information**
  - Title (text)
  - Slug (auto-generated, editable)
  - Type (dropdown: Hajj, Umrah, Tour)
  - Price (number + currency selector)
  - Duration (number + days)
  - Featured (toggle)
  - Active (toggle)

- **Media**
  - Main Image (upload with preview)
  - Gallery Images (multiple upload)

- **Features** (dynamic list)
  - Add/Remove feature items

- **Description** (rich text editor)

- **Itinerary** (dynamic day-by-day)
  - Day number
  - Title
  - Description
  - Activities list

- **Hotel Information**
  - Hotel name
  - Star rating
  - Location
  - Distance to Haram
  - Amenities (tags)

- **Inclusions/Exclusions** (two dynamic lists)

- **Departure Dates** (date picker, multiple)

- **Capacity**
  - Max capacity
  - Current bookings (display only)

### 3.3 Articles/Blog Management

#### List View
| Column | Actions |
|--------|---------|
| Thumbnail | View |
| Title | Edit |
| Category | Publish/Unpublish |
| Status | Delete |
| Published Date | |
| Views | |

#### Create/Edit Form
- **Basic Information**
  - Title
  - Slug (auto-generated)
  - Category (dropdown/create new)
  - Excerpt (textarea)

- **Content** (Rich Text Editor - TinyMCE/Tiptap)

- **Media**
  - Featured Image
  - In-content images

- **SEO**
  - Meta Title
  - Meta Description
  - Tags

- **Publishing**
  - Status (Draft/Published)
  - Publish Date (schedule)

### 3.4 Team Management

#### List View (Grid or Table)
| Column | Actions |
|--------|---------|
| Avatar | Edit |
| Name | Reorder (drag) |
| Role | Toggle Active |
| Social Links | Delete |

#### Create/Edit Form
- Name
- Role/Position
- Image Upload
- Bio (textarea)
- Email
- Phone
- Social Links (Facebook, Twitter, LinkedIn, Instagram)
- Display Order
- Active Status

### 3.5 Inquiries/Contacts

#### List View
| Column | Actions |
|--------|---------|
| Name | View Details |
| Email | Mark as Read |
| Subject | Respond |
| Status Badge | Delete |
| Package (if linked) | |
| Date | |

#### Detail View
- Full message content
- Admin notes (editable)
- Quick reply option
- Status changer
- Related package info (if any)

### 3.6 Testimonials

#### List View
| Column | Actions |
|--------|---------|
| Avatar | Edit |
| Name | Approve/Reject |
| Rating (stars) | Feature/Unfeature |
| Excerpt | Delete |
| Featured Badge | |
| Status | |

#### Create/Edit Form
- Name
- Location/Country
- Avatar Upload
- Rating (1-5 stars)
- Testimonial Text
- Linked Package (optional)
- Featured Toggle
- Approved Toggle

### 3.7 Settings

#### General Settings
- Company Name
- Logo Upload
- Email
- Phone
- Address

#### Office Locations
- Add/Edit/Remove offices
- Name, Address, Phone, Email, Map coordinates

#### Social Media Links
- Facebook, Twitter, Instagram, LinkedIn URLs

#### Homepage Settings
- Hero Banner Image
- Hero Title
- Hero Subtitle
- Featured Packages Selection

---

## 4. Admin Panel UI Structure

### 4.1 Layout Components Needed

```
admin/
├── layouts/
│   └── AdminLayout.vue          # Main admin wrapper
├── components/
│   ├── sidebar/
│   │   ├── AdminSidebar.vue     # Main navigation
│   │   ├── SidebarItem.vue      # Individual menu item
│   │   └── SidebarSection.vue   # Grouped items (Hajj, Tour, Typing)
│   ├── header/
│   │   ├── AdminHeader.vue      # Top bar
│   │   ├── UserDropdown.vue     # Admin user menu
│   │   └── NotificationBell.vue # Inquiry notifications
│   ├── common/
│   │   ├── DataTable.vue        # Reusable table
│   │   ├── SearchFilter.vue     # Search and filter bar
│   │   ├── Pagination.vue       # Page navigation
│   │   ├── ImageUploader.vue    # Single image upload
│   │   ├── GalleryUploader.vue  # Multiple images
│   │   ├── RichTextEditor.vue   # TinyMCE/Tiptap wrapper
│   │   ├── DynamicList.vue      # Add/remove items
│   │   ├── StatusBadge.vue      # Active/Inactive badges
│   │   ├── ConfirmModal.vue     # Delete confirmation
│   │   └── StatsCard.vue        # Dashboard stat cards
│   └── forms/
│       ├── PackageForm.vue      # Package create/edit
│       ├── ArticleForm.vue      # Article create/edit
│       ├── TeamMemberForm.vue   # Team member form
│       └── TestimonialForm.vue  # Testimonial form
└── pages/
    └── admin/
        ├── Dashboard.vue
        ├── hajj/
        │   ├── packages/
        │   │   ├── Index.vue    # List
        │   │   ├── Create.vue   # Create
        │   │   └── Edit.vue     # Edit
        │   ├── articles/
        │   │   ├── Index.vue
        │   │   ├── Create.vue
        │   │   └── Edit.vue
        │   ├── team/
        │   │   └── Index.vue    # CRUD in one page
        │   ├── testimonials/
        │   │   └── Index.vue
        │   ├── inquiries/
        │   │   ├── Index.vue
        │   │   └── Show.vue
        │   └── settings/
        │       └── Index.vue
        ├── tour/
        │   └── ... (placeholder)
        └── typing/
            └── ... (placeholder)
```

### 4.2 Navigation Structure

```
📊 Dashboard

📿 Hajj & Umrah
   ├── 📦 Packages
   │   ├── All Packages
   │   └── Add New
   ├── 📝 Articles
   │   ├── All Articles
   │   └── Add New
   ├── 👥 Team
   ├── 💬 Testimonials
   ├── 📩 Inquiries
   └── ⚙️ Settings

✈️ Tour & Travel (Coming Soon)
   └── ...

📄 Typing Services (Coming Soon)
   └── ...

👤 Profile
🚪 Logout
```

---

## 5. Technology Stack for Admin Panel

### Frontend
- **Vue 3** with Composition API
- **TypeScript** for type safety
- **Inertia.js** for SPA-like experience
- **Tailwind CSS v4** for styling
- **Alpine.js** for lightweight interactivity
- **Lucide Icons** (already in use)

### UI Components (Leverage Existing)
- Existing `ui/` components:
  - Button, Input, Label, Checkbox
  - Card, Dialog, Sheet
  - Dropdown Menu, Sidebar
  - Avatar, Badge, Tooltip
  - Skeleton (loading states)

### Additional Recommended
- **Tiptap** or **TinyMCE** for rich text editing
- **Vue Draggable** for reordering
- **FilePond** or custom uploader for images
- **ApexCharts** for dashboard charts

### Backend (Laravel)
- Laravel 12 Controllers
- Form Requests for validation
- Eloquent Models with relationships
- Laravel Storage for file uploads
- API Resources for consistent responses

---

## 6. Color Scheme & Design Tokens

Based on existing Hajj frontend:

| Token | Value | Usage |
|-------|-------|-------|
| Primary | `#D3A762` (Gold) | CTAs, highlights |
| Primary Hover | `#c29652` | Button hover |
| Secondary | `teal-900` | Headers, nav |
| Background | `slate-50` / `gray-50` | Page backgrounds |
| Surface | `white` | Cards, panels |
| Text Primary | `slate-900` | Headings |
| Text Secondary | `slate-600` | Body text |
| Text Muted | `slate-500` | Captions |
| Success | `green-500` | Active states |
| Warning | `yellow-500` | Alerts |
| Error | `red-500` | Errors, delete |

---

## 7. Responsive Considerations

| Breakpoint | Behavior |
|------------|----------|
| Mobile (<768px) | Collapsible sidebar, stacked layouts |
| Tablet (768-1024px) | Mini sidebar, 2-column grids |
| Desktop (>1024px) | Full sidebar, multi-column layouts |

---

## 8. Future Expansion Notes

### For Tour & Travel Section
- Similar package structure
- Destination management
- Booking system integration
- Tour guides management

### For Typing Services Section
- Service categories
- Pricing tiers
- Document types
- Order tracking

### Shared Features (Consider Now)
- User authentication (already exists)
- Media library (shared uploads)
- SEO settings module
- Email templates
- Notification system
- Activity logs

---

## 9. Summary

The Hajj section admin panel needs to manage:
1. **7 main data entities**: Packages, Articles, Team, Testimonials, Inquiries, Settings, Categories
2. **CRUD operations** for all entities
3. **Media management** for images
4. **Rich text editing** for articles and descriptions
5. **Status management** (active/inactive, published/draft)
6. **Dashboard analytics** for quick overview

The design should:
- Leverage existing UI components
- Maintain consistency with frontend styling
- Be scalable for Tour and Typing sections
- Use Laravel best practices for backend
- Implement proper validation and error handling
