# ⚙️ Dynamic Settings - Admin Panel Map

## 🗂️ File Structure Created

```
app/
├── Models/
│   ├── SiteSetting.php
│   ├── HeaderLink.php
│   └── FooterLink.php
├── Http/Controllers/Admin/
│   ├── SiteSettingController.php
│   ├── HeaderLinkController.php
│   └── FooterLinkController.php

database/
├── migrations/
│   ├── 2026_04_06_123000_create_site_settings_table.php
│   ├── 2026_04_06_123000_create_header_links_table.php
│   └── 2026_04_06_123000_create_footer_links_table.php
├── seeders/
│   └── SiteDataSeeder.php

resources/views/dashboard/settings/
├── site-settings.blade.php
├── header-links/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── footer-links/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php

resources/views/layout/
└── frontlayout.blade.php (UPDATED - now uses dynamic data)

resources/views/components/
└── sidebar.blade.php (UPDATED - added new menu items)

routes/
└── web.php (UPDATED - added new routes)
```

---

## 🚀 Admin Panel Access Routes

### Site Settings Management
```
URL: /dashboard/site-settings
Method: GET (view form)
Method: POST (save changes)
```

### Header Links Management
```
List all links      → GET    /dashboard/header-links
Create new link     → GET    /dashboard/header-links/create
Store link          → POST   /dashboard/header-links
Edit link           → GET    /dashboard/header-links/{id}/edit
Update link         → PUT    /dashboard/header-links/{id}
Delete link         → DELETE /dashboard/header-links/{id}
```

### Footer Links Management
```
List all links      → GET    /dashboard/footer-links
Create new link     → GET    /dashboard/footer-links/create
Store link          → POST   /dashboard/footer-links
Edit link           → GET    /dashboard/footer-links/{id}/edit
Update link         → PUT    /dashboard/footer-links/{id}
Delete link         → DELETE /dashboard/footer-links/{id}
```

---

## 🎯 Sidebar Navigation Path

```
Dashboard Home
    ↓
Settings (Dropdown)
    ├── 🔧 Site Settings          ← NEW
    ├── 🔗 Header Links           ← NEW
    ├── 🔗 Footer Links           ← NEW
    ├── 📄 Pages                  (existing)
    ├── Company                   (existing)
    ├── Notification              (existing)
    └── ... other settings
```

---

## 📊 Database Schema

### site_settings Table
```sql
id (integer, primary key)
site_name (string, 255)
tagline (text)
email (string, 255)
phone (string, 20)
address (text)
about (text)
facebook_url (string, nullable)
twitter_url (string, nullable)
instagram_url (string, nullable)
linkedin_url (string, nullable)
logo_path (string, nullable)
footer_logo_path (string, nullable)
footer_text (text, nullable)
created_at
updated_at
```

### header_links Table
```sql
id (integer, primary key)
label (string, 255)
url (string, 255)
icon (string, nullable)
position (integer)
is_active (boolean, default: true)
target (string: '_self' or '_blank')
created_at
updated_at
```

### footer_links Table
```sql
id (integer, primary key)
section (string, 255)
label (string, 255)
url (string, 255)
position (integer)
is_active (boolean, default: true)
target (string: '_self' or '_blank')
created_at
updated_at
```

---

## 🔑 Model Methods

### SiteSetting Methods
```php
SiteSetting::get($key, $default) // Get setting by key
SiteSetting::first() // Get all settings
```

### HeaderLink Methods
```php
HeaderLink::getActive() // Get all active links sorted by position
HeaderLink::create($data) // Create new link
HeaderLink::find($id) // Find by ID
```

### FooterLink Methods
```php
FooterLink::getBySection($section) // Get links in section
FooterLink::getSections() // Get all sections
FooterLink::create($data) // Create new link
```

---

## 🌍 Frontend Dynamic Display

### Header Navigation
**File:** `resources/views/layout/frontlayout.blade.php`

```blade
@php
    $headerLinks = \App\Models\HeaderLink::getActive();
@endphp
@foreach($headerLinks as $link)
    <li class="nav-item">
        <a class="nav-link" href="{{ $link->url }}" target="{{ $link->target }}">
            {{ $link->label }}
        </a>
    </li>
@endforeach
```

### Footer Navigation
**File:** `resources/views/layout/frontlayout.blade.php`

```blade
@php
    $settings = \App\Models\SiteSetting::first();
    $footerSections = \App\Models\FooterLink::selectRaw('DISTINCT section')
        ->where('is_active', true)
        ->pluck('section');
@endphp

@foreach($footerSections as $section)
    <div class="footer-section">
        <h5>{{ $section }}</h5>
        @php
            $links = \App\Models\FooterLink::where('section', $section)
                ->where('is_active', true)
                ->orderBy('position')
                ->get();
        @endphp
        @foreach($links as $link)
            <a href="{{ $link->url }}">{{ $link->label }}</a>
        @endforeach
    </div>
@endforeach
```

---

## 🔄 Controller Methods

### SiteSettingController
```php
edit()          // Show settings form
update()        // Save settings with file uploads
```

### HeaderLinkController
```php
index()         // List all header links (paginated)
create()        // Show create form
store()         // Save new link
edit()          // Show edit form
update()        // Update existing link
destroy()       // Delete link
```

### FooterLinkController
```php
index()         // List all footer links (paginated)
create()        // Show create form
store()         // Save new link
edit()          // Show edit form
update()        // Update existing link
destroy()       // Delete link
```

---

## ✅ Validation Rules

### SiteSetting Validation
```php
'site_name'       => 'required|string|max:255'
'tagline'         => 'required|string|max:500'
'email'           => 'required|email'
'phone'           => 'required|string|max:20'
'address'         => 'nullable|string|max:500'
'about'           => 'nullable|string'
'facebook_url'    => 'nullable|url'
'twitter_url'     => 'nullable|url'
'instagram_url'   => 'nullable|url'
'linkedin_url'    => 'nullable|url'
'footer_text'     => 'nullable|string'
```

### Header/Footer Link Validation
```php
'label'       => 'required|string|max:255'
'url'         => 'required|string|max:255'
'icon'        => 'nullable|string|max:255'
'section'     => 'required|string|max:255' (footer only)
'position'    => 'required|integer'
'is_active'   => 'boolean'
'target'      => 'required|in:_self,_blank'
```

---

## 🧪 Testing URLs

### Access Admin Panel
```
GET /admin → Redirects to /authentication/signin
GET /authentication/signin → Admin login
```

### After Login, Access Settings
```
GET /dashboard/site-settings → View settings form
POST /dashboard/site-settings → Save settings

GET /dashboard/header-links → List header navigation
GET /dashboard/header-links/create → Add new link form
POST /dashboard/header-links → Save new link
GET /dashboard/header-links/1/edit → Edit specific link
PUT /dashboard/header-links/1 → Update link
DELETE /dashboard/header-links/1 → Delete link

GET /dashboard/footer-links → List footer links
GET /dashboard/footer-links/create → Add new link form
POST /dashboard/footer-links → Save new link
GET /dashboard/footer-links/1/edit → Edit specific link
PUT /dashboard/footer-links/1 → Update link
DELETE /dashboard/footer-links/1 → Delete link
```

---

## 🎨 Frontend URLs to Test

```
GET / → Home (uses dynamic header & footer)
GET /products → Products page (uses dynamic header & footer)
GET /about → About page (uses dynamic header & footer)
GET /technology → Technology page (uses dynamic header & footer)
GET /impact → Impact page (uses dynamic header & footer)
GET /contact → Contact page (uses dynamic header & footer)
```

All pages now load dynamic header, footer, and site info!

---

## 🔧 Sample Seeded Data

After running `php artisan db:seed --class=SiteDataSeeder`:

**Site Settings (1 record):**
- Name: Bharat Biomer
- Email: admin@bharatbiomer.com
- Phone: +91 7828333334

**Header Links (6 records):**
1. Home (position 1)
2. Technology (position 2)
3. Products (position 3)
4. About Us (position 4)
5. Impact (position 5)
6. Contact (position 6)

**Footer Links (6 records):**
- Products section: Bio-stimulants, Microbial Solutions
- Company section: About Us, Technology
- Contact section: Email Us, Call Us

---

## 🚀 Quick Commands

```bash
# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed --class=SiteDataSeeder

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Start development server
php artisan serve
```

---

**All systems dynamic and configurable from admin panel! ✨**
