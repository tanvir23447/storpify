-- ==============================================================================
-- MAISON ELORA: PREMIUM BEAUTY & CARE
-- SEED DATA: seed_data.sql
-- ==============================================================================

-- 1. BRAND ASSETS (Section 0 Initial Brand Logo Reference)
INSERT INTO public.brand_assets (
    id, asset_type, asset_name, storage_path, public_url_or_path, alt_text, width, height, mime_type, file_size, is_active
) VALUES 
('00000000-0000-0000-0000-000000000001', 'primary', 'Maison Elora Official Primary Logo', 'brand/maison-elora-logo.jpg', '/images/brand/maison-elora-logo.jpg', 'Maison Elora - Premium Beauty & Care Golden Monogram Logo', 1024, 1024, 'image/jpeg', 333848, TRUE),
('00000000-0000-0000-0000-000000000002', 'compact', 'Maison Elora Header Compact Logo', 'brand/maison-elora-logo.jpg', '/images/brand/maison-elora-logo.jpg', 'Maison Elora Compact Header Logo', 512, 512, 'image/jpeg', 333848, TRUE),
('00000000-0000-0000-0000-000000000003', 'mobile', 'Maison Elora Mobile Header Logo', 'brand/maison-elora-logo.jpg', '/images/brand/maison-elora-logo.jpg', 'Maison Elora Mobile Logo', 256, 256, 'image/jpeg', 333848, TRUE),
('00000000-0000-0000-0000-000000000004', 'footer', 'Maison Elora Luxury Footer Logo', 'brand/maison-elora-logo.jpg', '/images/brand/maison-elora-logo.jpg', 'Maison Elora Footer Logo', 512, 512, 'image/jpeg', 333848, TRUE),
('00000000-0000-0000-0000-000000000005', 'admin', 'Maison Elora Super Admin Logo', 'brand/maison-elora-logo.jpg', '/images/brand/maison-elora-logo.jpg', 'Maison Elora Admin Logo', 512, 512, 'image/jpeg', 333848, TRUE)
ON CONFLICT (id) DO NOTHING;

-- 2. BRAND SETTINGS
INSERT INTO public.brand_settings (
    id, brand_name, tagline, primary_color, secondary_color, accent_color, rose_nude_color, charcoal_color,
    header_announcement_en, header_announcement_bn,
    contact_phone, contact_email, whatsapp_number, business_address,
    facebook_url, instagram_url, is_active
) VALUES (
    '11111111-1111-1111-1111-111111111111',
    'Maison Elora',
    'Premium Beauty & Care',
    '#F6D7D9',
    '#FFF9F2',
    '#C9A45C',
    '#C68B91',
    '#292426',
    'Free Delivery on Orders Over ৳2,000 (Inside Dhaka) | 100% Authentic Imported Products | Easy Return & Exchange',
    '২,০০০ টাকার অর্ডারে ফ্রি ডেলিভারি (ঢাকার ভেতরে) | ১০০% অরিজিনাল আমদানিকৃত পণ্য | সহজ রিটার্ন ও এক্সচেঞ্জ',
    '+8801700000000',
    'contact@maisonelora.com',
    '+8801700000000',
    'Gulshan 2, Dhaka - 1212, Bangladesh',
    'https://facebook.com/maisonelora',
    'https://instagram.com/maisonelora',
    TRUE
)
ON CONFLICT (id) DO UPDATE SET
    brand_name = EXCLUDED.brand_name,
    tagline = EXCLUDED.tagline;

-- 3. CATEGORIES
INSERT INTO public.categories (id, name_en, name_bn, slug, subtitle_en, subtitle_bn, image_url, sort_order, is_active) VALUES
('22222222-2222-2222-2222-222222220001', 'Skincare', 'স্কিনকেয়ার', 'skincare', 'Face Care • Serum • Moisturizer', 'ফেস কেয়ার • সিরাম • ময়েশ্চারাইজার', 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80', 1, TRUE),
('22222222-2222-2222-2222-222222220002', 'Cosmetics', 'কসমেটিকস', 'cosmetics', 'Makeup • Lip Care • Beauty Essentials', 'মেকআপ • লিপ কেয়ার • বিউটি এসেনশিয়াল', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=600&q=80', 2, TRUE),
('22222222-2222-2222-2222-222222220003', 'Perfumes', 'সুগন্ধি / পারফিউম', 'perfumes', 'Women • Men • Unisex Fragrance', 'নারী • পুরুষ • ইউনিসেক্স সুবাস', 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=600&q=80', 3, TRUE),
('22222222-2222-2222-2222-222222220004', 'Chocolates', 'চকলেট', 'chocolates', 'Imported • Gift Box • Premium Treats', 'আমদানিকৃত • গিফট বক্স • প্রিমিয়াম ট্রিট', 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?auto=format&fit=crop&w=600&q=80', 4, TRUE)
ON CONFLICT (id) DO NOTHING;

-- 4. BRANDS
INSERT INTO public.brands (id, name, slug, country_of_origin, is_featured) VALUES
('33333333-3333-3333-3333-333333330001', 'The Ordinary', 'the-ordinary', 'Canada', TRUE),
('33333333-3333-3333-3333-333333330002', 'COSRX', 'cosrx', 'South Korea', TRUE),
('33333333-3333-3333-3333-333333330003', 'LANEIGE', 'laneige', 'South Korea', TRUE),
('33333333-3333-3333-3333-333333330004', 'Versace', 'versace', 'Italy', TRUE),
('33333333-3333-3333-3333-333333330005', 'Lancôme', 'lancome', 'France', TRUE),
('33333333-3333-3333-3333-333333330006', 'La Roche-Posay', 'la-roche-posay', 'France', TRUE),
('33333333-3333-3333-3333-333333330007', 'Yves Saint Laurent', 'ysl', 'France', TRUE),
('33333333-3333-3333-3333-333333330008', 'Estée Lauder', 'estee-lauder', 'USA', TRUE),
('33333333-3333-3333-3333-333333330009', 'Ferrero Rocher', 'ferrero-rocher', 'Italy', TRUE),
('33333333-3333-3333-3333-333333330010', 'Godiva', 'godiva', 'Belgium', TRUE)
ON CONFLICT (id) DO NOTHING;

-- 5. SUPPLIERS
INSERT INTO public.suppliers (id, name, contact_person, email, country, is_active) VALUES
('44444444-4444-4444-4444-444444440001', 'EuroLux Logistics UK', 'Alexander Wright', 'orders@eurolux-uk.co.uk', 'United Kingdom', TRUE),
('44444444-4444-4444-4444-444444440002', 'K-Beauty Direct Seoul', 'Min-Ji Kim', 'distribution@kbeautydirect.kr', 'South Korea', TRUE),
('44444444-4444-4444-4444-444444440003', 'ChocoGourmet Belgium', 'Jean Dupont', 'export@chocogourmet.be', 'Belgium', TRUE)
ON CONFLICT (id) DO NOTHING;

-- 6. PRODUCTS (Matching Attachment 02 Homepage Reference exactly)
INSERT INTO public.products (
    id, name_en, name_bn, slug, sku, category_id, brand_id,
    short_description_en, short_description_bn,
    description_en, description_bn,
    price, compare_at_price, cost_price,
    volume_weight, country_of_origin,
    is_new_arrival, is_best_seller, is_featured, rating, review_count,
    skin_type, skin_concerns,
    fragrance_family, concentration,
    chocolate_flavor,
    authenticity_notes
) VALUES
-- 1. The Ordinary Niacinamide
(
    '55555555-5555-5555-5555-555555550001',
    'The Ordinary Niacinamide 10% + Zinc 1%',
    'দি অর্ডিনারি নিয়াসিনামাইড ১০% + জিংক ১%',
    'the-ordinary-niacinamide-10-zinc-1',
    'SKU-TO-NIA-30',
    '22222222-2222-2222-2222-222222220001',
    '33333333-3333-3333-3333-333333330001',
    'High-strength vitamin and mineral blemish formula that reduces the appearance of skin blemishes and congestion.',
    'ত্বকের দাগ ও পোরস দূর করতে অত্যন্ত কার্যকর ভিটামিন ও মিনারেল সমৃদ্ধ লাইটওয়েট সিরাম।',
    'Niacinamide (Vitamin B3) is indicated to reduce the appearance of skin blemishes and congestion. A high 10% concentration of this vitamin is supported in the formula by zinc salt of pyrrolidone carboxylic acid to balance visible aspects of sebum activity.',
    'নিয়াসিনামাইড (ভিটামিন বি৩) ত্বকের অতিরিক্ত তেল ও ব্রণের দাগ হ্রাস করে ত্বককে মসৃণ ও উজ্জ্বল করে।',
    1490.00, 1690.00, 950.00,
    '30 ml', 'Canada',
    TRUE, TRUE, TRUE, 4.9, 128,
    ARRAY['All', 'Oily', 'Combination'], ARRAY['Acne', 'Large Pores', 'Oiliness'],
    NULL, NULL, NULL,
    'Imported directly from Deciem Canada authorized retail inventory.'
),

-- 2. COSRX Snail Mucin
(
    '55555555-5555-5555-5555-555555550002',
    'COSRX Advanced Snail 96 Mucin Power Essence',
    'কসআরএক্স অ্যাডভান্সড স্নেইল ৯৬ মুসিন এসেন্স',
    'cosrx-advanced-snail-96-mucin-essence',
    'SKU-COS-SNAIL-100',
    '22222222-2222-2222-2222-222222220001',
    '33333333-3333-3333-3333-333333330002',
    'Formulated with 96.3% Snail Secretion Filtrate, this essence repairs and rejuvenates dry and damaged skin.',
    '৯৬.৩% স্নেইল সিক্রেশন সমৃদ্ধ ডিপ হাইড্রেশন ও গ্লোয়িং স্কিন এসেন্স।',
    'Lightweight essence which absorbs quickly into skin to give you natural glow from inside. Creates a moisture barrier to plump, hydrate, and soothe skin.',
    'ত্বকের গভীর থেকে আর্দ্রতা ফিরিয়ে আনে এবং ড্যামেজড স্কিন রিপেয়ার করে প্রাকৃতিক জেল্লা প্রদান করে।',
    1990.00, 2250.00, 1300.00,
    '100 ml', 'South Korea',
    TRUE, TRUE, TRUE, 4.95, 214,
    ARRAY['Dry', 'Sensitive', 'Normal', 'Combination'], ARRAY['Dryness', 'Barrier Damage', 'Dullness'],
    NULL, NULL, NULL,
    'Directly imported from official Korean wholesale export hubs.'
),

-- 3. LANEIGE Lip Sleeping Mask
(
    '55555555-5555-5555-5555-555555550003',
    'LANEIGE Lip Sleeping Mask (Berry)',
    'লানেজ লিপ স্লিপিং মাস্ক (বেরি)',
    'laneige-lip-sleeping-mask-berry',
    'SKU-LAN-LIP-20',
    '22222222-2222-2222-2222-222222220002',
    '33333333-3333-3333-3333-333333330003',
    'A leave-on lip mask that delivers intense moisture and antioxidants while you sleep.',
    'রাতের বেলা ঠোঁটের রুক্ষতা দূর করে কোমল ও গোলাপি করার প্রিমিয়াম লিপ মাস্ক।',
    'Enriched with vitamin C and antioxidants, Berry Fruit Complex offers a nutritiously sweet and fragrant blend of raspberry, strawberry, cranberry, and blueberry extracts.',
    'ভিটামিন সি এবং বেরি ফ্রুট কমপ্লেক্স সমৃদ্ধ যা শুষ্ক ও ফাটা ঠোঁটকে নরম, হাইড্রেটেড ও উজ্জ্বল করে।',
    1650.00, 1850.00, 1050.00,
    '20 g', 'South Korea',
    TRUE, TRUE, TRUE, 4.88, 97,
    ARRAY['All'], ARRAY['Dry Lips', 'Chapped Lips'],
    NULL, NULL, NULL,
    'Original Amorepacific seal verified imported batch.'
),

-- 4. Versace Bright Crystal
(
    '55555555-5555-5555-5555-555555550004',
    'Versace Bright Crystal Eau de Toilette',
    'ভারসাচ ব্রাইট ক্রিস্টাল পারফিউম',
    'versace-bright-crystal-edt-90ml',
    'SKU-VER-CRYSTAL-90',
    '22222222-2222-2222-2222-222222220003',
    '33333333-3333-3333-3333-333333330004',
    'A precious jewel of rare beauty characterized by a fresh, vibrant, flowery scent.',
    'ইতালিয়ান বিলাসবহুল ফ্লোরাল ও ফ্রুটি সুবাসের দীর্ঘস্থায়ী লাক্সারি সুগন্ধি।',
    'Top notes of pomegranate, yuzu, and frosted accord. Floral heart of peony and magnolia with a sensual base of amber, acajou, and musk.',
    'ডালিম, ইউজু এবং পিওনি ফুলের মেলবন্ধনে তৈরি বিশ্বখ্যাত লাক্সারি পারফিউম।',
    8990.00, 9990.00, 6200.00,
    '90 ml', 'Italy',
    TRUE, TRUE, TRUE, 4.96, 64,
    NULL, NULL,
    'Floral Fruity Musky', 'EDT', NULL,
    'EuroLux certified European duty-free origin with original barcode batch.'
),

-- 5. Lancôme Advanced Génifique
(
    '55555555-5555-5555-5555-555555550005',
    'Lancôme Advanced Génifique Youth Activating Concentrate',
    'ল্যাঙ্কোম অ্যাডভান্সড জেনিফিক কনসেন্ট্রেট',
    'lancome-advanced-genifique-concentrate',
    'SKU-LAN-GEN-50',
    '22222222-2222-2222-2222-222222220001',
    '33333333-3333-3333-3333-333333330005',
    'Iconic anti-aging serum engineered with 7 pre- and probiotic fractions for fast recovery.',
    'স্কিন মাইক্রোবায়োম ও অ্যান্টি-এজিং রিকভারির জন্য বিশ্বসেরা লাক্সারি সিরাম।',
    'Strengthens skin moisture barrier and delivers visibly smoother, more radiant skin in 7 days.',
    'মাত্র ৭ দিনে ত্বকের তারুণ্য ও স্বাভাবিক টানটান উজ্জ্বলতা ফিরিয়ে আনে।',
    14500.00, 16000.00, 10200.00,
    '50 ml', 'France',
    FALSE, TRUE, TRUE, 5.0, 42,
    ARRAY['All', 'Mature', 'Sensitive'], ARRAY['Anti-aging', 'Fine Lines', 'Firmness'],
    NULL, NULL, NULL,
    'Genuine French manufactured stock with authenticated batch number.'
),

-- 6. Ferrero Rocher T24
(
    '55555555-5555-5555-5555-555555550006',
    'Ferrero Rocher Premium Gold T24 Imported Chocolate Gift Box',
    'ফেরেরো রোশার গোল্ড টি২৪ চকলেট গিফট বক্স',
    'ferrero-rocher-gold-t24-300g',
    'SKU-FER-T24-300',
    '22222222-2222-2222-2222-222222220004',
    '33333333-3333-3333-3333-333333330009',
    'A whole hazelnut surrounded by delicious layers of crispy wafer, velvety cream, and finely chopped hazelnuts.',
    'খাস্তা ওয়েফার ও রোস্টেড হ্যাজেলনাট ক্রিমের বিলাসবহুল ইতালিয়ান চকলেট গিফট প্যাক।',
    'A timeless classic presented in an iconic transparent gift box with golden foil wrappers.',
    'বিশেষ উৎসব ও প্রিয়জনকে উপহার দেওয়ার সেরা প্রিমিয়াম চকলেট বক্স।',
    1850.00, 2100.00, 1200.00,
    '300 g', 'Italy',
    TRUE, TRUE, TRUE, 4.98, 88,
    NULL, NULL,
    NULL, NULL, 'Crispy Hazelnut & Milk Chocolate',
    'Temperature-controlled air-freighted European imported stock.'
)
ON CONFLICT (id) DO NOTHING;

-- 7. PRODUCT IMAGES
INSERT INTO public.product_images (id, product_id, image_url, alt_text, sort_order, is_primary) VALUES
('66666666-6666-6666-6666-666666660001', '55555555-5555-5555-5555-555555550001', 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80', 'The Ordinary Niacinamide 10% + Zinc 1%', 0, TRUE),
('66666666-6666-6666-6666-666666660002', '55555555-5555-5555-5555-555555550002', 'https://images.unsplash.com/photo-1608248597359-548d4c1b12b5?auto=format&fit=crop&w=800&q=80', 'COSRX Advanced Snail 96 Mucin Power Essence', 0, TRUE),
('66666666-6666-6666-6666-666666660003', '55555555-5555-5555-5555-555555550003', 'https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=800&q=80', 'LANEIGE Lip Sleeping Mask Berry', 0, TRUE),
('66666666-6666-6666-6666-666666660004', '55555555-5555-5555-5555-555555550004', 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=800&q=80', 'Versace Bright Crystal Eau de Toilette', 0, TRUE),
('66666666-6666-6666-6666-666666660005', '55555555-5555-5555-5555-555555550005', 'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=800&q=80', 'Lancôme Advanced Génifique Concentrate', 0, TRUE),
('66666666-6666-6666-6666-666666660006', '55555555-5555-5555-5555-555555550006', 'https://images.unsplash.com/photo-1549007994-cb92caebd54b?auto=format&fit=crop&w=800&q=80', 'Ferrero Rocher Gold T24 Imported Chocolate', 0, TRUE)
ON CONFLICT (id) DO NOTHING;

-- 8. INVENTORY BATCHES (Section 8 FEFO Implementation)
INSERT INTO public.inventory_batches (
    id, batch_number, product_id, supplier_id, purchase_date, manufacturing_date, expiry_date,
    initial_quantity, available_quantity, purchase_cost, status, notes
) VALUES
('77777777-7777-7777-7777-777777770001', 'BATCH-TO-2026-A1', '55555555-5555-5555-5555-555555550001', '44444444-4444-4444-4444-444444440001', '2026-01-10', '2025-11-01', '2028-11-01', 50, 42, 950.00, 'active', 'Fresh batch direct shipment'),
('77777777-7777-7777-7777-777777770002', 'BATCH-COS-2026-B4', '55555555-5555-5555-5555-555555550002', '44444444-4444-4444-4444-444444440002', '2026-02-01', '2025-12-15', '2028-12-15', 60, 51, 1300.00, 'active', 'Air-freighted from Incheon'),
('77777777-7777-7777-7777-777777770003', 'BATCH-LAN-2026-C2', '55555555-5555-5555-5555-555555550003', '44444444-4444-4444-4444-444444440002', '2026-02-15', '2026-01-05', '2029-01-05', 40, 36, 1050.00, 'active', 'Berry 20g standard'),
('77777777-7777-7777-7777-777777770004', 'BATCH-VER-2026-V9', '55555555-5555-5555-5555-555555550004', '44444444-4444-4444-4444-444444440001', '2026-01-20', '2025-10-01', '2030-10-01', 25, 21, 6200.00, 'active', 'EDT 90ml luxury stock'),
('77777777-7777-7777-7777-777777770005', 'BATCH-LAN-2026-G1', '55555555-5555-5555-5555-555555550005', '44444444-4444-4444-4444-444444440001', '2026-01-25', '2025-09-01', '2028-09-01', 15, 12, 10200.00, 'active', 'Lancôme youth concentrate'),
('77777777-7777-7777-7777-777777770006', 'BATCH-FER-2026-F5', '55555555-5555-5555-5555-555555550006', '44444444-4444-4444-4444-444444440003', '2026-03-01', '2026-02-01', '2027-02-01', 50, 39, 1200.00, 'active', 'Cool storage stored chocolate')
ON CONFLICT (id) DO NOTHING;

-- 9. COUPONS
INSERT INTO public.coupons (id, code, discount_type, discount_value, min_order_amount, is_active) VALUES
('88888888-8888-8888-8888-888888880001', 'ELORA10', 'percentage', 10.00, 2000.00, TRUE),
('88888888-8888-8888-8888-888888880002', 'WELCOME200', 'fixed_amount', 200.00, 1500.00, TRUE),
('88888888-8888-8888-8888-888888880003', 'FREESHIP', 'free_shipping', 0.00, 1000.00, TRUE)
ON CONFLICT (id) DO NOTHING;
