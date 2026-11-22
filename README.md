# Maison Elora — Premium Beauty & Care
### Complete Enterprise E-commerce Ecosystem for Imported Luxury Beauty, Skincare, Perfumes & Chocolates

![Maison Elora Brand](/frontend/public/images/brand/maison-elora-logo.jpg)

**Maison Elora** is a complete, secure, production-grade e-commerce application built for Bangladesh's luxury beauty and care market. It features a bilingual storefront (English & Bengali), an interactive Super Admin Dashboard, FEFO batch-based inventory and expiry management, Bangladesh MFS payment workflows (Cash on Delivery, bKash, Nagad, Rocket), and a dynamic brand identity and logo management engine.

---

## 🌟 Key Highlights

1. **Bespoke Luxury Visual Identity**:
   - Blush Pink (`#F6D7D9`), Soft Ivory (`#FFF9F2`), Champagne Gold (`#C9A45C`), Rose Nude (`#C68B91`), Deep Charcoal (`#292426`).
   - High-fidelity recreation of the Maison Elora design reference.
   - Dual-language support (English & Bengali: `English | বাংলা`).

2. **Dynamic Logo & Brand Identity Management (Section 1)**:
   - Super Admin module at `Settings → Brand Identity & Logo Management`.
   - Admin can upload, preview, replace, and restore:
     - Primary Website Logo
     - Compact Header Logo
     - Mobile Logo
     - Footer Logo
     - Admin Dashboard Logo
     - Favicon
     - Open Graph / Social Sharing Image
   - Changes propagate across the entire website instantly without editing source code.

3. **Complete Shopping System**:
   - Shop by category: Skincare, Cosmetics, Perfumes, Chocolates.
   - Luxury product cards with discount tags, original strikethrough prices in BDT (৳), wishlist toggle, quick add to cart.
   - Rich product details with cosmetic attributes (ingredients, skin type, country of origin, batch number, expiry date).
   - Interactive slide-out cart drawer with instant quantity controls and coupon codes.
   - Comprehensive checkout with Bangladesh divisions, districts, upazilas, delivery notes, and configurable shipping rules.

4. **Bangladesh Payment Methods**:
   - Cash on Delivery (COD)
   - Manual bKash, Nagad, Rocket with step-by-step merchant number display, transaction ID input, and receipt validation.
   - Admin payment verification workflow (Pending, Submitted, Verified, Rejected).

5. **Advanced Inventory & Expiry Management (FEFO)**:
   - Batch-level tracking: batch numbers, manufacturing dates, expiry dates, supplier costs.
   - First Expiry, First Out (FEFO) automated allocation.
   - Near-expiry (<90 days) and low-stock (<5 units) automated alerts.

6. **Super Admin Dashboard**:
   - 24+ administrative views including Sales Analytics, Products, Categories, Inventory & Batches, Orders, Payments, Customers, Coupons, CMS Banners, Shipping, and Brand Settings.

---

## 🚀 Getting Started

Read [ENVIRONMENT_SETUP.md](./ENVIRONMENT_SETUP.md) for full instructions.

```powershell
# 1. Install dependencies
cd frontend
npm install

# 2. Run locally
npm run dev
```

Storefront: [http://localhost:3000](http://localhost:3000)  
Super Admin: [http://localhost:3000/admin](http://localhost:3000/admin)

---

## 🛡️ Security & Architecture
- Supabase PostgreSQL with 38+ enterprise tables.
- Row Level Security (RLS) enabled on all tables.
- Server Actions & Zod validation.
- Zero credentials leak: Safe local fallback mock adapter when live credentials are absent.
"# storpify" 
