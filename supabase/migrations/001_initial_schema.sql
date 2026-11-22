-- ==============================================================================
-- MAISON ELORA: PREMIUM BEAUTY & CARE
-- COMPLETE ENTERPRISE POSTGRESQL / SUPABASE DATABASE SCHEMA
-- Migration: 001_initial_schema.sql
-- ==============================================================================

-- Enable UUID extension
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ------------------------------------------------------------------------------
-- 1. PROFILES & ROLES
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.profiles (
    id UUID PRIMARY KEY REFERENCES auth.users(id) ON DELETE CASCADE,
    email TEXT UNIQUE NOT NULL,
    full_name TEXT NOT NULL,
    phone TEXT,
    avatar_url TEXT,
    role TEXT NOT NULL DEFAULT 'customer' CHECK (role IN ('super_admin', 'store_manager', 'order_manager', 'inventory_manager', 'content_manager', 'customer')),
    is_blocked BOOLEAN NOT NULL DEFAULT FALSE,
    is_vip BOOLEAN NOT NULL DEFAULT FALSE,
    notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.user_roles (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES public.profiles(id) ON DELETE CASCADE,
    role TEXT NOT NULL CHECK (role IN ('super_admin', 'store_manager', 'order_manager', 'inventory_manager', 'content_manager')),
    assigned_by UUID REFERENCES public.profiles(id),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE(user_id, role)
);

CREATE TABLE IF NOT EXISTS public.role_permissions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    role TEXT NOT NULL,
    permission TEXT NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE(role, permission)
);

-- ------------------------------------------------------------------------------
-- 2. BRAND ASSETS & BRAND SETTINGS (SECTION 1 MANDATORY REQUIREMENT)
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.brand_assets (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    asset_type TEXT NOT NULL CHECK (asset_type IN ('primary', 'compact', 'mobile', 'footer', 'admin', 'favicon', 'social', 'email', 'banner')),
    asset_name TEXT NOT NULL,
    storage_path TEXT NOT NULL,
    public_url_or_path TEXT NOT NULL,
    alt_text TEXT,
    width INTEGER,
    height INTEGER,
    mime_type TEXT,
    file_size INTEGER,
    is_active BOOLEAN NOT NULL DEFAULT FALSE,
    uploaded_by UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.brand_settings (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    brand_name TEXT NOT NULL DEFAULT 'Maison Elora',
    tagline TEXT NOT NULL DEFAULT 'Premium Beauty & Care',
    primary_color TEXT NOT NULL DEFAULT '#F6D7D9',
    secondary_color TEXT NOT NULL DEFAULT '#FFF9F2',
    accent_color TEXT NOT NULL DEFAULT '#C9A45C',
    rose_nude_color TEXT NOT NULL DEFAULT '#C68B91',
    charcoal_color TEXT NOT NULL DEFAULT '#292426',
    header_announcement_en TEXT DEFAULT 'Free Delivery on Orders Over ৳2,000 (Inside Dhaka) | 100% Authentic Imported Products | Easy Return & Exchange',
    header_announcement_bn TEXT DEFAULT '২,০০০ টাকার অর্ডারে ফ্রি ডেলিভারি (ঢাকার ভেতরে) | ১০০% অরিজিনাল আমদানিকৃত পণ্য | সহজ রিটার্ন ও এক্সচেঞ্জ',
    contact_phone TEXT DEFAULT '+8801700000000',
    contact_email TEXT DEFAULT 'contact@maisonelora.com',
    whatsapp_number TEXT DEFAULT '+8801700000000',
    business_address TEXT DEFAULT 'Gulshan 2, Dhaka - 1212, Bangladesh',
    facebook_url TEXT DEFAULT 'https://facebook.com/maisonelora',
    instagram_url TEXT DEFAULT 'https://instagram.com/maisonelora',
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.site_settings (
    key TEXT PRIMARY KEY,
    value JSONB NOT NULL,
    description TEXT,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ------------------------------------------------------------------------------
-- 3. CATEGORIES, BRANDS & SUPPLIERS
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.categories (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name_en TEXT NOT NULL,
    name_bn TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description_en TEXT,
    description_bn TEXT,
    subtitle_en TEXT, -- e.g. "Face Care • Serum • Moisturizer"
    subtitle_bn TEXT,
    image_url TEXT,
    icon_name TEXT,
    parent_id UUID REFERENCES public.categories(id) ON DELETE SET NULL,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.brands (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    country_of_origin TEXT,
    logo_url TEXT,
    description TEXT,
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.suppliers (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name TEXT NOT NULL,
    contact_person TEXT,
    email TEXT,
    phone TEXT,
    address TEXT,
    country TEXT,
    notes TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ------------------------------------------------------------------------------
-- 4. PRODUCTS, VARIANTS & ATTRIBUTES
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.products (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name_en TEXT NOT NULL,
    name_bn TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    sku TEXT UNIQUE NOT NULL,
    category_id UUID NOT NULL REFERENCES public.categories(id) ON DELETE RESTRICT,
    brand_id UUID REFERENCES public.brands(id) ON DELETE SET NULL,
    description_en TEXT,
    description_bn TEXT,
    short_description_en TEXT,
    short_description_bn TEXT,
    price NUMERIC(12, 2) NOT NULL CHECK (price >= 0),
    compare_at_price NUMERIC(12, 2) CHECK (compare_at_price >= price),
    cost_price NUMERIC(12, 2) CHECK (cost_price >= 0),
    is_featured BOOLEAN NOT NULL DEFAULT FALSE,
    is_new_arrival BOOLEAN NOT NULL DEFAULT FALSE,
    is_best_seller BOOLEAN NOT NULL DEFAULT FALSE,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    rating NUMERIC(3, 2) DEFAULT 5.00,
    review_count INTEGER DEFAULT 0,
    
    -- Beauty Specific Attributes
    country_of_origin TEXT,
    manufacturer TEXT,
    volume_weight TEXT, -- e.g. "30 ml", "100 ml", "90 ml"
    skin_type TEXT[], -- e.g. {"All", "Dry", "Oily", "Combination", "Sensitive"}
    skin_concerns TEXT[], -- e.g. {"Acne", "Hyperpigmentation", "Anti-aging", "Dryness"}
    ingredients_en TEXT,
    ingredients_bn TEXT,
    usage_instructions_en TEXT,
    usage_instructions_bn TEXT,
    benefits_en TEXT[],
    benefits_bn TEXT[],
    warnings_en TEXT,
    warnings_bn TEXT,
    authenticity_notes TEXT DEFAULT '100% genuine imported directly from verified brand distributors in the EU, UK, USA & Korea.',
    
    -- Fragrance Attributes
    fragrance_family TEXT,
    top_notes TEXT,
    middle_notes TEXT,
    base_notes TEXT,
    concentration TEXT, -- "EDP", "EDT", "Parfum"
    
    -- Chocolate Attributes
    chocolate_flavor TEXT,
    allergens TEXT,
    storage_instructions TEXT,
    
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.product_variants (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    sku TEXT UNIQUE NOT NULL,
    title TEXT NOT NULL, -- e.g. "30ml", "50ml", "Shade 01 Nude"
    option1_name TEXT, -- "Size", "Color", "Shade"
    option1_value TEXT,
    option2_name TEXT,
    option2_value TEXT,
    price NUMERIC(12, 2) NOT NULL CHECK (price >= 0),
    compare_at_price NUMERIC(12, 2),
    stock_quantity INTEGER NOT NULL DEFAULT 0,
    barcode TEXT,
    image_url TEXT,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.product_images (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    image_url TEXT NOT NULL,
    alt_text TEXT,
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_primary BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.product_attributes (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    attribute_name TEXT NOT NULL,
    attribute_value TEXT NOT NULL
);

-- ------------------------------------------------------------------------------
-- 5. ADVANCED INVENTORY, BATCHES & FEFO EXPIRY MANAGEMENT (SECTION 8)
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.inventory_batches (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_number TEXT NOT NULL UNIQUE,
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE RESTRICT,
    variant_id UUID REFERENCES public.product_variants(id) ON DELETE RESTRICT,
    supplier_id UUID REFERENCES public.suppliers(id) ON DELETE SET NULL,
    purchase_date DATE NOT NULL,
    manufacturing_date DATE,
    expiry_date DATE NOT NULL,
    initial_quantity INTEGER NOT NULL CHECK (initial_quantity >= 0),
    available_quantity INTEGER NOT NULL CHECK (available_quantity >= 0),
    purchase_cost NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    status TEXT NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'near_expiry', 'expired', 'depleted', 'quarantine')),
    notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.stock_movements (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    batch_id UUID REFERENCES public.inventory_batches(id) ON DELETE SET NULL,
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE RESTRICT,
    variant_id UUID REFERENCES public.product_variants(id) ON DELETE RESTRICT,
    movement_type TEXT NOT NULL CHECK (movement_type IN ('received', 'allocated_order', 'fulfilled_order', 'cancelled_order', 'damaged', 'expired_write_off', 'return_restock', 'manual_adjustment')),
    quantity INTEGER NOT NULL, -- Positive for addition, negative for deduction
    reference_id TEXT, -- e.g. order_id, return_id, receiving_voucher
    reason TEXT,
    performed_by UUID REFERENCES public.profiles(id),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.order_batch_allocations (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL,
    order_item_id UUID NOT NULL,
    batch_id UUID NOT NULL REFERENCES public.inventory_batches(id) ON DELETE RESTRICT,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ------------------------------------------------------------------------------
-- 6. CARTS & WISHLISTS
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.carts (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE,
    guest_session_id TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    CONSTRAINT user_or_guest CHECK (user_id IS NOT NULL OR guest_session_id IS NOT NULL)
);

CREATE TABLE IF NOT EXISTS public.cart_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    cart_id UUID NOT NULL REFERENCES public.carts(id) ON DELETE CASCADE,
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    variant_id UUID REFERENCES public.product_variants(id) ON DELETE SET NULL,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.wishlists (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID NOT NULL REFERENCES public.profiles(id) ON DELETE CASCADE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE(user_id)
);

CREATE TABLE IF NOT EXISTS public.wishlist_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    wishlist_id UUID NOT NULL REFERENCES public.wishlists(id) ON DELETE CASCADE,
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    UNIQUE(wishlist_id, product_id)
);

-- ------------------------------------------------------------------------------
-- 7. ADDRESSES, ORDERS, ITEMS & SHIPMENTS
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.addresses (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE,
    recipient_name TEXT NOT NULL,
    phone TEXT NOT NULL,
    division TEXT NOT NULL,
    district TEXT NOT NULL,
    upazila TEXT,
    area TEXT,
    street_address TEXT NOT NULL,
    delivery_notes TEXT,
    is_default BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.orders (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_number TEXT NOT NULL UNIQUE,
    user_id UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    customer_name TEXT NOT NULL,
    customer_email TEXT,
    customer_phone TEXT NOT NULL,
    
    -- Address Snapshot
    shipping_division TEXT NOT NULL,
    shipping_district TEXT NOT NULL,
    shipping_upazila TEXT,
    shipping_address TEXT NOT NULL,
    delivery_instructions TEXT,
    
    -- Financials Snapshot (in BDT)
    subtotal NUMERIC(12, 2) NOT NULL CHECK (subtotal >= 0),
    discount_amount NUMERIC(12, 2) NOT NULL DEFAULT 0.00 CHECK (discount_amount >= 0),
    coupon_code TEXT,
    shipping_cost NUMERIC(12, 2) NOT NULL DEFAULT 0.00 CHECK (shipping_cost >= 0),
    total_amount NUMERIC(12, 2) NOT NULL CHECK (total_amount >= 0),
    
    -- Statuses
    order_status TEXT NOT NULL DEFAULT 'pending' CHECK (order_status IN ('pending', 'confirmed', 'processing', 'packed', 'shipped', 'out_for_delivery', 'delivered', 'cancelled', 'return_requested', 'returned')),
    payment_status TEXT NOT NULL DEFAULT 'pending' CHECK (payment_status IN ('pending', 'submitted', 'under_review', 'verified', 'rejected', 'refunded')),
    payment_method TEXT NOT NULL CHECK (payment_method IN ('cod', 'bkash', 'nagad', 'rocket', 'card')),
    
    notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.order_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE CASCADE,
    product_id UUID REFERENCES public.products(id) ON DELETE RESTRICT,
    variant_id UUID REFERENCES public.product_variants(id) ON DELETE SET NULL,
    product_name TEXT NOT NULL,
    product_sku TEXT NOT NULL,
    unit_price NUMERIC(12, 2) NOT NULL CHECK (unit_price >= 0),
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    total_price NUMERIC(12, 2) NOT NULL CHECK (total_price >= 0),
    batch_allocated_id UUID REFERENCES public.inventory_batches(id),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.order_status_history (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE CASCADE,
    status TEXT NOT NULL,
    comment TEXT,
    changed_by UUID REFERENCES public.profiles(id),
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.payments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE CASCADE,
    payment_method TEXT NOT NULL,
    amount NUMERIC(12, 2) NOT NULL CHECK (amount >= 0),
    status TEXT NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'submitted', 'under_review', 'verified', 'rejected', 'refunded')),
    transaction_id TEXT,
    sender_phone TEXT,
    payment_proof_url TEXT,
    verified_by UUID REFERENCES public.profiles(id),
    verified_at TIMESTAMPTZ,
    rejection_reason TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.shipments (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE CASCADE,
    courier_name TEXT NOT NULL, -- e.g. "Pathao", "Steadfast", "RedX", "eCourier", "Internal"
    tracking_number TEXT,
    tracking_url TEXT,
    shipping_charge NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    dispatched_at TIMESTAMPTZ,
    estimated_delivery DATE,
    delivered_at TIMESTAMPTZ,
    status TEXT NOT NULL DEFAULT 'ready' CHECK (status IN ('ready', 'dispatched', 'in_transit', 'delivered', 'failed', 'returned')),
    notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ------------------------------------------------------------------------------
-- 8. COUPONS, REVIEWS, RETURNS & CMS
-- ------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS public.coupons (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    code TEXT UNIQUE NOT NULL,
    discount_type TEXT NOT NULL CHECK (discount_type IN ('percentage', 'fixed_amount', 'free_shipping')),
    discount_value NUMERIC(12, 2) NOT NULL CHECK (discount_value > 0),
    min_order_amount NUMERIC(12, 2) DEFAULT 0.00,
    max_discount_cap NUMERIC(12, 2),
    usage_limit INTEGER,
    used_count INTEGER NOT NULL DEFAULT 0,
    start_date TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    end_date TIMESTAMPTZ,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.coupon_redemptions (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    coupon_id UUID NOT NULL REFERENCES public.coupons(id) ON DELETE RESTRICT,
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE CASCADE,
    user_id UUID REFERENCES public.profiles(id),
    discount_applied NUMERIC(12, 2) NOT NULL,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.reviews (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    product_id UUID NOT NULL REFERENCES public.products(id) ON DELETE CASCADE,
    user_id UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    customer_name TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    is_verified_purchase BOOLEAN NOT NULL DEFAULT FALSE,
    is_approved BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.return_requests (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id UUID NOT NULL REFERENCES public.orders(id) ON DELETE RESTRICT,
    user_id UUID REFERENCES public.profiles(id),
    status TEXT NOT NULL DEFAULT 'requested' CHECK (status IN ('requested', 'approved', 'item_received', 'inspected', 'refunded', 'rejected')),
    reason TEXT NOT NULL,
    action_requested TEXT NOT NULL CHECK (action_requested IN ('refund', 'replacement', 'store_credit')),
    admin_notes TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.return_items (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    return_request_id UUID NOT NULL REFERENCES public.return_requests(id) ON DELETE CASCADE,
    order_item_id UUID NOT NULL REFERENCES public.order_items(id) ON DELETE RESTRICT,
    quantity INTEGER NOT NULL CHECK (quantity > 0),
    condition TEXT CHECK (condition IN ('unopened', 'damaged_in_transit', 'defective', 'opened_cannot_restock'))
);

CREATE TABLE IF NOT EXISTS public.banners (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    title_en TEXT NOT NULL,
    title_bn TEXT,
    subtitle_en TEXT,
    subtitle_bn TEXT,
    cta_text_en TEXT DEFAULT 'Shop Now',
    cta_text_bn TEXT DEFAULT 'এখনই কিনুন',
    cta_url TEXT NOT NULL DEFAULT '/shop',
    image_url TEXT NOT NULL,
    position TEXT NOT NULL DEFAULT 'hero_slider' CHECK (position IN ('hero_slider', 'middle_promo', 'bottom_banner', 'category_banner')),
    sort_order INTEGER NOT NULL DEFAULT 0,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.homepage_sections (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    section_key TEXT UNIQUE NOT NULL, -- e.g. "announcement", "hero_slider", "trust_strip", "shop_category", "new_arrivals", "best_sellers", "skincare_spotlight", "perfumes_spotlight", "chocolates_spotlight", "reviews", "newsletter"
    title_en TEXT NOT NULL,
    title_bn TEXT NOT NULL,
    subtitle_en TEXT,
    subtitle_bn TEXT,
    is_enabled BOOLEAN NOT NULL DEFAULT TRUE,
    sort_order INTEGER NOT NULL DEFAULT 0,
    config JSONB DEFAULT '{}'::jsonb,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.pages (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    slug TEXT UNIQUE NOT NULL,
    title_en TEXT NOT NULL,
    title_bn TEXT NOT NULL,
    content_en TEXT,
    content_bn TEXT,
    is_published BOOLEAN NOT NULL DEFAULT TRUE,
    updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.faqs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    question_en TEXT NOT NULL,
    question_bn TEXT NOT NULL,
    answer_en TEXT NOT NULL,
    answer_bn TEXT NOT NULL,
    category TEXT DEFAULT 'general',
    sort_order INTEGER DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE
);

CREATE TABLE IF NOT EXISTS public.newsletter_subscribers (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    email TEXT UNIQUE NOT NULL,
    status TEXT NOT NULL DEFAULT 'subscribed',
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.notifications (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.profiles(id) ON DELETE CASCADE,
    title TEXT NOT NULL,
    message TEXT NOT NULL,
    link TEXT,
    is_read BOOLEAN NOT NULL DEFAULT FALSE,
    is_admin_notification BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS public.audit_logs (
    id UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id UUID REFERENCES public.profiles(id) ON DELETE SET NULL,
    action TEXT NOT NULL,
    entity_name TEXT NOT NULL,
    entity_id TEXT,
    changes JSONB,
    ip_address TEXT,
    user_agent TEXT,
    created_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

-- ------------------------------------------------------------------------------
-- 9. ROW LEVEL SECURITY (RLS) POLICIES
-- ------------------------------------------------------------------------------

ALTER TABLE public.profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.brand_assets ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.brand_settings ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.site_settings ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.categories ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.brands ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.products ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.product_variants ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.product_images ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.inventory_batches ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.stock_movements ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.orders ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.order_items ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.payments ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.banners ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.homepage_sections ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.reviews ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.coupons ENABLE ROW LEVEL SECURITY;
ALTER TABLE public.audit_logs ENABLE ROW LEVEL SECURITY;

-- Helper security function: Check if current auth user is admin
CREATE OR REPLACE FUNCTION public.is_admin()
RETURNS BOOLEAN AS $$
BEGIN
  RETURN EXISTS (
    SELECT 1 FROM public.profiles
    WHERE id = auth.uid() AND role IN ('super_admin', 'store_manager', 'order_manager', 'inventory_manager', 'content_manager')
  );
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Public can read active brand assets & brand settings
CREATE POLICY "Public can view active brand assets" ON public.brand_assets FOR SELECT USING (is_active = TRUE OR public.is_admin());
CREATE POLICY "Admins can manage brand assets" ON public.brand_assets FOR ALL USING (public.is_admin());

CREATE POLICY "Public can view active brand settings" ON public.brand_settings FOR SELECT USING (is_active = TRUE);
CREATE POLICY "Admins can manage brand settings" ON public.brand_settings FOR ALL USING (public.is_admin());

-- Public can read active catalog
CREATE POLICY "Public can view active categories" ON public.categories FOR SELECT USING (is_active = TRUE OR public.is_admin());
CREATE POLICY "Public can view brands" ON public.brands FOR SELECT TO PUBLIC USING (TRUE);
CREATE POLICY "Public can view active products" ON public.products FOR SELECT USING (is_active = TRUE OR public.is_admin());
CREATE POLICY "Public can view active product variants" ON public.product_variants FOR SELECT USING (is_active = TRUE OR public.is_admin());
CREATE POLICY "Public can view product images" ON public.product_images FOR SELECT TO PUBLIC USING (TRUE);
CREATE POLICY "Public can view active banners" ON public.banners FOR SELECT USING (is_active = TRUE OR public.is_admin());
CREATE POLICY "Public can view homepage sections" ON public.homepage_sections FOR SELECT USING (is_enabled = TRUE OR public.is_admin());
CREATE POLICY "Public can view approved reviews" ON public.reviews FOR SELECT USING (is_approved = TRUE);

-- Customer orders & payments
CREATE POLICY "Users can view own orders" ON public.orders FOR SELECT USING (auth.uid() = user_id OR public.is_admin());
CREATE POLICY "Users can create orders" ON public.orders FOR INSERT WITH CHECK (TRUE);
CREATE POLICY "Admins can update orders" ON public.orders FOR UPDATE USING (public.is_admin());

CREATE POLICY "Users can view own order items" ON public.order_items FOR SELECT USING (
    EXISTS (SELECT 1 FROM public.orders WHERE orders.id = order_items.order_id AND (orders.user_id = auth.uid() OR public.is_admin()))
);

CREATE POLICY "Users can submit payment proof" ON public.payments FOR INSERT WITH CHECK (TRUE);
CREATE POLICY "Users view own payments" ON public.payments FOR SELECT USING (
    EXISTS (SELECT 1 FROM public.orders WHERE orders.id = payments.order_id AND (orders.user_id = auth.uid() OR public.is_admin()))
);
CREATE POLICY "Admins can manage payments" ON public.payments FOR ALL USING (public.is_admin());

-- Inventory & Batch management (Restricted to Admins)
CREATE POLICY "Admins manage inventory batches" ON public.inventory_batches FOR ALL USING (public.is_admin());
CREATE POLICY "Admins view stock movements" ON public.stock_movements FOR ALL USING (public.is_admin());

-- Coupons
CREATE POLICY "Public can view active coupons" ON public.coupons FOR SELECT USING (is_active = TRUE);
CREATE POLICY "Admins manage coupons" ON public.coupons FOR ALL USING (public.is_admin());

-- Audit logs
CREATE POLICY "Admins view audit logs" ON public.audit_logs FOR SELECT USING (public.is_admin());
