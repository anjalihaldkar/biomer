# 🔧 Dynamic Header, Footer & Settings System

> **Complete guide to managing your website content from the admin panel**

---

## 📋 Overview

Your website is now fully dynamic! All header, footer, and site-wide settings can be managed from the admin dashboard without touching code.

### Quick Access
- **Site Settings**: Dashboard → Settings → Site Settings
- **Header Links**: Dashboard → Settings → Header Links  
- **Footer Links**: Dashboard → Settings → Footer Links

---

## 🌐 What Can Be Managed

### 1. **Site Settings** (Global Configuration)
**Path:** `/dashboard/site-settings`

#### Basic Information
- 📝 **Site Name** - Your company/brand name
- 💬 **Tagline** - Short company description
- 📖 **About** - Detailed company information

#### Contact Information
- 📧 **Email** - Primary contact email
- 📞 **Phone** - Contact phone number
- 📍 **Address** - Business address

#### Social Media
- 👍 **Facebook URL** - Facebook page link
- 🐦 **Twitter URL** - Twitter profile link
- 📸 **Instagram URL** - Instagram profile link
- 💼 **LinkedIn URL** - LinkedIn company link

#### Branding
- 🖼️ **Header Logo** - Site logo (shown in navbar)
- 🖼️ **Footer Logo** - Footer logo
- 📝 **Footer Text** - Copyright and footer information

**Result:** These settings appear in:
- Website footer (email, phone)
- Page footer (logo)
- Social links (when integrated)

---

### 2. **Header Navigation** (Menu Items)
**Path:** `/dashboard/header-links`

#### What's Currently Displayed
```
Home → Technology → Products → About Us → Impact → Contact
[Cart Icon] [Wishlist Icon] [Login/Account]
```

#### How to Manage
- ✏️ **Edit** any menu item
- ➕ **Add** new navigation items
- 🗑️ **Delete** links you don't need
- 👁️ **Toggle Active** - Show/hide without deleting

#### For Each Link
- **Label** - What appears in menu (e.g., "Home")
- **URL** - Where it goes (e.g., "/" or "/products")
- **Icon** - Optional icon (Iconify library)
- **Position** - Order in menu (1=first, 2=second, etc.)
- **Target** - Open in same tab or new tab
- **Status** - Active/Inactive

#### Example: How to Add a New Menu Item
1. Go to Header Links
2. Click "+ Add Link"
3. Fill in:
   - Label: "Blog"
   - URL: "/blog"
   - Position: 7 (after Contact)
   - Target: Same tab
4. Click "Create Link"
5. It now appears in your header menu!

**Result:** Menu updates live on all website pages

---

### 3. **Footer Links** (Footer Navigation)
**Path:** `/dashboard/footer-links`

#### What's Currently Organized By

**Products Section**
- Bio-stimulants
- Microbial Solutions

**Company Section**  
- About Us
- Technology

**Contact Section**
- Email Us (mailto link)
- Call Us (tel link)

#### How to Manage
- ✏️ **Edit** any footer link
- ➕ **Add** new footer sections/links
- 🗑️ **Delete** links
- 🔄 **Reorder** within sections by changing position

#### For Each Link
- **Section** - Category heading (e.g., "Products", "Company")
- **Label** - Link text (e.g., "Bio-stimulants")
- **URL** - Where it points
- **Position** - Order within that section (1=first)
- **Target** - Same tab or new tab
- **Status** - Active/Inactive

#### Example: Add a New Section
1. Go to Footer Links
2. Click "+ Add Link"
3. Fill in:
   - Section: "Legal"
   - Label: "Privacy Policy"
   - URL: "/privacy-policy"
   - Position: 1
4. Click "Create Link"
5. New "Legal" section appears in footer!

**Result:** Footer automatically reorganizes with new sections

---

## 🎯 Real-World Examples

### Example 1: Add Blog Section to Menu
**Goal:** Show blog link in the main navigation

1. Settings → Header Links
2. "+ Add Link"
3. Fill in:
   - Label: `Blog`
   - URL: `/blog`
   - Position: `7` (after Contact)
   - Status: `Active`
4. Save → Blog now appears in header menu

### Example 2: Update Contact Information
**Goal:** Update phone number and email

1. Settings → Site Settings
2. Update:
   - Email: `newemail@company.com`
   - Phone: `+91 9876543210`
3. Save → All locations updated (footer, contact page, etc.)

### Example 3: Add New Footer Category
**Goal:** Add "Support" section with help links

1. Settings → Footer Links
2. "+ Add Link" - First support link
   - Section: `Support`
   - Label: `FAQ`
   - URL: `/faq`
3. "+ Add Link" - Second support link
   - Section: `Support`
   - Label: `Contact Support`
   - URL: `/support`

Result: New "Support" section appears in footer with both links

### Example 4: Hide a Menu Item Temporarily
**Goal:** Temporarily hide "Technology" menu

1. Settings → Header Links
2. Find "Technology" → Click Edit
3. Uncheck "Active" → Save
4. Technology disappears from header (not deleted, just hidden)

---

## 🚀 Features & Benefits

✅ **No Code Changes Needed** - Update everything from admin panel
✅ **Live Updates** - Changes appear immediately on website
✅ **Flexible Sections** - Create any footer sections you want
✅ **Icon Support** - Add icons from Iconify library
✅ **Easy Toggle** - Show/hide items without deleting
✅ **Multiple Logos** - Different logos for header and footer
✅ **Social Links** - Centralized social media URLs
✅ **Reorderable** - Simple position numbers to rearrange

---

## 💾 Database Tables

If you're curious about the backend:

- **site_settings** - Global site information (1 record)
- **header_links** - Navigation menu items (sortable)
- **footer_links** - Footer sections and links (sortable by section)

Each item has:
- Active/Inactive toggle
- Customizable position for sorting
- Optional icon support
- Open in same/new tab option

---

## 🔄 How It Works

### Frontend Display Flow

```
User visits website
         ↓
Header loads → Queries HeaderLink (active only)
         ↓
Displays in position order
         ↓
Footer loads → Queries FooterLink grouped by section
         ↓
Displays organized sections
         ↓
Site settings → Loaded for logo, footer text, social links
```

### Caching
- Settings are loaded fresh each page load
- No complex caching, always current
- Changes appear immediately

---

## 📝 Tips & Best Practices

1. **Link URLs** - Use relative paths:
   - ✅ Good: `/`, `/products`, `/blog`
   - ❌ Avoid: `http://example.com/products`

2. **Position Numbers** - Keep gaps for future items:
   - Header: 1, 2, 3, 5, 10 (leave room to insert at 4)
   - Footer: 1, 2, 5 instead of 1, 2, 3

3. **Active/Inactive** - Use instead to keep useful items
   - Don't delete footer links, just deactivate
   - Easier to restore later

4. **Sections** - Footer links are grouped by section name
   - Use consistent capitalization: "Products" not "products"
   - Create new section by using new section name

5. **Icons** - Optional but adds visual appeal
   - Visit [Iconify][https://iconify.design/] for icon names
   - Example: `lucide:home`, `ri-home-line`

---

## 🎨 Available Customization

### What You Control:
- ✅ All navigation menu items
- ✅ All footer content and structure
- ✅ Company information (email, phone, social)
- ✅ Logos and branding
- ✅ Link targets (same/new tab)
- ✅ Item ordering/positioning
- ✅ Show/hide without deleting

### What's Automated:
- ✅ Frontend rendering (you just provide data)
- ✅ Responsive design
- ✅ Link validation
- ✅ Data persistence

---

## 📊 Summary of Admin Pages

| Page | Purpose | Details |
|------|---------|---------|
| Site Settings | Global config | Logo, contact, social, footer text |
| Header Links | Navigation | Menu items in header navigation |
| Footer Links | Footer nav | Organized footer sections & links |

**All accessible via:** Dashboard → Settings → [Choose one]

---

## ✨ What's Currently Seeded

### Header Menu:
1. Home (/)
2. Technology (/technology)
3. Products (/products)
4. About Us (/about)
5. Impact (/impact)
6. Contact (/contact)

### Footer Sections:

**Products**
- Bio-stimulants (/products)
- Microbial Solutions (/products)

**Company**
- About Us (/about)
- Technology (/technology)

**Contact**
- Email Us (mailto:admin@bharatbiomer.com)
- Call Us (tel:+917828333334)

### Site Settings:
- Name: Bharat Biomer
- Email: admin@bharatbiomer.com
- Phone: +91 7828333334
- Country: India

---

**Created with ♻️ Full Dynamic Support**

All changes you make are reflected immediately on your live website!
