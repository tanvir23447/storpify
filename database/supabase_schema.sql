-- ============================================================
-- STORPIFY — SUPABASE POSTGRESQL PRODUCTION SCHEMA
-- Complete Normalized Relational Architecture with RLS & Policies
-- ============================================================

-- Enable pgcrypto extension for secure UUID & hashing if needed
CREATE EXTENSION IF NOT EXISTS pgcrypto;

-- 1. ROLES & PERMISSIONS
CREATE TABLE IF NOT EXISTS roles (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    is_system BOOLEAN DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS permissions (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    key VARCHAR(255) UNIQUE NOT NULL,
    module VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS user_roles (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role_id BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, role_id)
);

CREATE TABLE IF NOT EXISTS role_permissions (
    id BIGSERIAL PRIMARY KEY,
    role_id BIGINT NOT NULL REFERENCES roles(id) ON DELETE CASCADE,
    permission_id BIGINT NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(role_id, permission_id)
);

CREATE TABLE IF NOT EXISTS user_permission_overrides (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    permission_id BIGINT NOT NULL REFERENCES permissions(id) ON DELETE CASCADE,
    is_granted BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, permission_id)
);

-- 2. MAIN STORE CATEGORIES & PRODUCTS
CREATE TABLE IF NOT EXISTS product_categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_bn TEXT,
    featured_image VARCHAR(255),
    icon VARCHAR(255),
    parent_id BIGINT REFERENCES product_categories(id) ON DELETE SET NULL,
    sort_order INT DEFAULT 0,
    seo_title VARCHAR(255),
    seo_description TEXT,
    status VARCHAR(50) DEFAULT 'active',
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE TABLE IF NOT EXISTS products (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    sku VARCHAR(255) UNIQUE,
    product_type VARCHAR(100) DEFAULT 'digital',
    short_description TEXT,
    short_description_bn TEXT,
    full_description TEXT,
    full_description_bn TEXT,
    thumbnail VARCHAR(255),
    image_gallery JSONB,
    demo_url VARCHAR(500),
    video_preview_url VARCHAR(500),
    regular_price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    sale_price NUMERIC(12, 2),
    is_featured BOOLEAN DEFAULT false,
    status VARCHAR(50) DEFAULT 'published',
    version VARCHAR(50) DEFAULT '1.0.0',
    update_info TEXT,
    download_instructions TEXT,
    download_limit INT DEFAULT 0,
    requires_license BOOLEAN DEFAULT false,
    seo_title VARCHAR(255),
    seo_description TEXT,
    view_count BIGINT DEFAULT 0,
    download_count BIGINT DEFAULT 0,
    sales_count BIGINT DEFAULT 0,
    avg_rating NUMERIC(3, 2) DEFAULT 5.00,
    review_count INT DEFAULT 0,
    created_by BIGINT REFERENCES users(id) ON DELETE SET NULL,
    approved_by BIGINT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX IF NOT EXISTS idx_products_slug ON products(slug);
CREATE INDEX IF NOT EXISTS idx_products_status ON products(status);
CREATE INDEX IF NOT EXISTS idx_products_featured ON products(is_featured);

-- 3. PRODUCT VARIANTS & FILES
CREATE TABLE IF NOT EXISTS product_variants (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    description TEXT,
    sku VARCHAR(255),
    regular_price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    sale_price NUMERIC(12, 2),
    download_limit INT DEFAULT 0,
    requires_license BOOLEAN DEFAULT false,
    status VARCHAR(50) DEFAULT 'active',
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS product_files (
    id BIGSERIAL PRIMARY KEY,
    product_id BIGINT NOT NULL REFERENCES products(id) ON DELETE CASCADE,
    variant_id BIGINT REFERENCES product_variants(id) ON DELETE SET NULL,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    storage_bucket VARCHAR(255) DEFAULT 'storpify-product-downloads',
    file_size BIGINT DEFAULT 0,
    file_type VARCHAR(50),
    version VARCHAR(50) DEFAULT '1.0.0',
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 4. E-COMMERCE & PAYMENTS
CREATE TABLE IF NOT EXISTS payment_methods (
    id BIGSERIAL PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    account_type VARCHAR(50) DEFAULT 'merchant',
    account_numbers JSONB NOT NULL,
    instructions TEXT,
    instructions_bn TEXT,
    qr_code VARCHAR(255),
    logo VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    fee_percentage NUMERIC(5, 2) DEFAULT 0.00,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id BIGSERIAL PRIMARY KEY,
    order_number VARCHAR(100) UNIQUE NOT NULL,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    subtotal NUMERIC(12, 2) NOT NULL,
    discount_amount NUMERIC(12, 2) DEFAULT 0.00,
    tax_amount NUMERIC(12, 2) DEFAULT 0.00,
    total_amount NUMERIC(12, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'BDT',
    payment_status VARCHAR(50) DEFAULT 'pending',
    order_status VARCHAR(50) DEFAULT 'pending',
    coupon_code VARCHAR(100),
    notes TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE INDEX IF NOT EXISTS idx_orders_order_number ON orders(order_number);
CREATE INDEX IF NOT EXISTS idx_orders_user ON orders(user_id);
CREATE INDEX IF NOT EXISTS idx_orders_payment_status ON orders(payment_status);

CREATE TABLE IF NOT EXISTS order_items (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    product_id BIGINT REFERENCES products(id) ON DELETE SET NULL,
    variant_id BIGINT REFERENCES product_variants(id) ON DELETE SET NULL,
    product_name VARCHAR(255) NOT NULL,
    variant_name VARCHAR(255),
    price NUMERIC(12, 2) NOT NULL,
    quantity INT DEFAULT 1,
    subtotal NUMERIC(12, 2) NOT NULL,
    download_count INT DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS payments (
    id BIGSERIAL PRIMARY KEY,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    user_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    payment_method_id BIGINT REFERENCES payment_methods(id) ON DELETE SET NULL,
    method_code VARCHAR(50) NOT NULL,
    transaction_id VARCHAR(255),
    sender_number VARCHAR(50),
    receiver_number VARCHAR(50),
    amount NUMERIC(12, 2) NOT NULL,
    currency VARCHAR(10) DEFAULT 'BDT',
    proof_file VARCHAR(500),
    payment_type VARCHAR(50) DEFAULT 'manual',
    status VARCHAR(50) DEFAULT 'pending',
    verified_by BIGINT REFERENCES users(id) ON DELETE SET NULL,
    verified_at TIMESTAMP WITH TIME ZONE,
    rejection_reason TEXT,
    gateway_response JSONB,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- 5. WEB SUBSCRIPTION MANAGEMENT (SEPARATE MODULE)
CREATE TABLE IF NOT EXISTS subscription_plans (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    description TEXT,
    description_bn TEXT,
    duration_type VARCHAR(50) DEFAULT 'days',
    duration_days INT DEFAULT 30,
    price NUMERIC(12, 2) NOT NULL DEFAULT 0.00,
    sale_price NUMERIC(12, 2),
    download_limit_daily INT DEFAULT 0,
    download_limit_total INT DEFAULT 0,
    device_limit INT DEFAULT 1,
    display_image VARCHAR(255),
    is_featured BOOLEAN DEFAULT false,
    is_active BOOLEAN DEFAULT true,
    all_categories_access BOOLEAN DEFAULT true,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS subscription_categories (
    id BIGSERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    name_bn VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_bn TEXT,
    featured_image VARCHAR(255),
    icon VARCHAR(255),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    seo_title VARCHAR(255),
    seo_description TEXT,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE TABLE IF NOT EXISTS subscription_files (
    id BIGSERIAL PRIMARY KEY,
    category_id BIGINT NOT NULL REFERENCES subscription_categories(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    title_bn VARCHAR(255),
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    description_bn TEXT,
    thumbnail VARCHAR(255),
    file_path VARCHAR(500) NOT NULL,
    storage_bucket VARCHAR(255) DEFAULT 'storpify-subscription-library',
    file_size BIGINT DEFAULT 0,
    file_type VARCHAR(50),
    version VARCHAR(50) DEFAULT '1.0.0',
    download_count BIGINT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP WITH TIME ZONE
);

CREATE TABLE IF NOT EXISTS user_subscriptions (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    plan_id BIGINT NOT NULL REFERENCES subscription_plans(id) ON DELETE CASCADE,
    order_id BIGINT,
    starts_at TIMESTAMP WITH TIME ZONE NOT NULL,
    ends_at TIMESTAMP WITH TIME ZONE NOT NULL,
    status VARCHAR(50) DEFAULT 'active',
    total_downloads INT DEFAULT 0,
    is_auto_renew BOOLEAN DEFAULT false,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_user_sub_user_status ON user_subscriptions(user_id, status);
CREATE INDEX IF NOT EXISTS idx_user_sub_ends_at ON user_subscriptions(ends_at);

-- 6. HOMEPAGE CMS
CREATE TABLE IF NOT EXISTS hero_slides (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_bn VARCHAR(255),
    subtitle VARCHAR(255),
    subtitle_bn VARCHAR(255),
    description TEXT,
    description_bn TEXT,
    desktop_image VARCHAR(500),
    mobile_image VARCHAR(500),
    bg_image VARCHAR(500),
    bg_color VARCHAR(50) DEFAULT '#17152B',
    overlay_opacity NUMERIC(3, 2) DEFAULT 0.00,
    text_color VARCHAR(50) DEFAULT '#FFFFFF',
    primary_button_text VARCHAR(100),
    primary_button_url VARCHAR(500),
    secondary_button_text VARCHAR(100),
    secondary_button_url VARCHAR(500),
    sort_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT true,
    starts_at TIMESTAMP WITH TIME ZONE,
    ends_at TIMESTAMP WITH TIME ZONE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS popup_campaigns (
    id BIGSERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_bn VARCHAR(255),
    description TEXT,
    description_bn TEXT,
    offer_image VARCHAR(500),
    discount_text VARCHAR(100),
    coupon_code VARCHAR(100),
    button_text VARCHAR(100),
    button_url VARCHAR(500),
    bg_color VARCHAR(50) DEFAULT '#17152B',
    text_color VARCHAR(50) DEFAULT '#FFFFFF',
    display_delay_sec INT DEFAULT 3,
    show_on_homepage BOOLEAN DEFAULT true,
    show_on_products BOOLEAN DEFAULT true,
    show_on_all BOOLEAN DEFAULT false,
    frequency VARCHAR(50) DEFAULT 'once_per_session',
    is_active BOOLEAN DEFAULT true,
    starts_at TIMESTAMP WITH TIME ZONE,
    ends_at TIMESTAMP WITH TIME ZONE,
    priority INT DEFAULT 1,
    view_count BIGINT DEFAULT 0,
    click_count BIGINT DEFAULT 0,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SUPABASE ROW LEVEL SECURITY (RLS) POLICIES
-- ============================================================
ALTER TABLE products ENABLE ROW LEVEL SECURITY;
ALTER TABLE product_categories ENABLE ROW LEVEL SECURITY;
ALTER TABLE subscription_plans ENABLE ROW LEVEL SECURITY;
ALTER TABLE subscription_categories ENABLE ROW LEVEL SECURITY;
ALTER TABLE orders ENABLE ROW LEVEL SECURITY;
ALTER TABLE user_subscriptions ENABLE ROW LEVEL SECURITY;

-- Public can read active products
CREATE POLICY "Public Read Active Products" ON products
    FOR SELECT USING (status = 'published');

-- Public can read active categories
CREATE POLICY "Public Read Active Categories" ON product_categories
    FOR SELECT USING (status = 'active');

-- Public can read active subscription plans
CREATE POLICY "Public Read Active Subscription Plans" ON subscription_plans
    FOR SELECT USING (is_active = true);

-- Service role has full unrestricted access on all tables
-- Laravel backend connects via Session Pooler or Service Role Key
