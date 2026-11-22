import { create } from 'zustand';
import { persist, createJSONStorage } from 'zustand/middleware';
import { Product, Category, BrandAsset, BrandSettings, InventoryBatch, CartItem, Order, Coupon, AssetType, OrderStatus, PaymentStatus } from '../types';

// Default initial brand assets using the supplied reference image
const defaultBrandAssets: BrandAsset[] = [
  {
    id: 'asset-default-primary',
    asset_type: 'primary',
    asset_name: 'Maison Elora Official Primary Header Logo',
    storage_path: 'brand/header-logo-reference.jpg',
    public_url_or_path: '/images/brand/header-logo-reference.jpg',
    alt_text: 'Maison Elora - Premium Beauty & Care Golden Monogram Logo',
    width: 195,
    height: 42,
    mime_type: 'image/jpeg',
    file_size: 15420,
    is_active: true,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
  {
    id: 'asset-default-compact',
    asset_type: 'compact',
    asset_name: 'Maison Elora Compact Header Logo',
    storage_path: 'brand/header-logo-reference.jpg',
    public_url_or_path: '/images/brand/header-logo-reference.jpg',
    alt_text: 'Maison Elora Compact Header Logo',
    width: 195,
    height: 42,
    mime_type: 'image/jpeg',
    file_size: 15420,
    is_active: true,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
  {
    id: 'asset-default-mobile',
    asset_type: 'mobile',
    asset_name: 'Maison Elora Mobile Header Logo',
    storage_path: 'brand/maison-elora-logo.jpg',
    public_url_or_path: '/images/brand/maison-elora-logo.jpg',
    alt_text: 'Maison Elora Mobile Header Logo',
    width: 256,
    height: 256,
    mime_type: 'image/jpeg',
    file_size: 333848,
    is_active: true,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
  {
    id: 'asset-default-footer',
    asset_type: 'footer',
    asset_name: 'Maison Elora Footer Logo',
    storage_path: 'brand/maison-elora-logo.jpg',
    public_url_or_path: '/images/brand/maison-elora-logo.jpg',
    alt_text: 'Maison Elora Luxury Footer Logo',
    width: 512,
    height: 512,
    mime_type: 'image/jpeg',
    file_size: 333848,
    is_active: true,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
  {
    id: 'asset-default-admin',
    asset_type: 'admin',
    asset_name: 'Maison Elora Super Admin Logo',
    storage_path: 'brand/maison-elora-logo.jpg',
    public_url_or_path: '/images/brand/maison-elora-logo.jpg',
    alt_text: 'Maison Elora Admin Logo',
    width: 512,
    height: 512,
    mime_type: 'image/jpeg',
    file_size: 333848,
    is_active: true,
    created_at: new Date().toISOString(),
    updated_at: new Date().toISOString(),
  },
];

const defaultBrandSettings: BrandSettings = {
  id: 'settings-default-01',
  brand_name: 'Maison Elora',
  tagline: 'Premium Beauty & Care',
  primary_color: '#F6D7D9',
  secondary_color: '#FFF9F2',
  accent_color: '#C9A45C',
  rose_nude_color: '#C68B91',
  charcoal_color: '#292426',
  header_announcement_en: 'Free Delivery on Orders Over ৳2,000 (Inside Dhaka) | 100% Authentic Imported Products | Easy Return & Exchange',
  header_announcement_bn: '২,০০০ টাকার অর্ডারে ফ্রি ডেলিভারি (ঢাকার ভেতরে) | ১০০% অরিজিনাল আমদানিকৃত পণ্য | সহজ রিটার্ন ও এক্সচেঞ্জ',
  contact_phone: '+8801700000000',
  contact_email: 'contact@maisonelora.com',
  whatsapp_number: '+8801700000000',
  business_address: 'Gulshan 2, Dhaka - 1212, Bangladesh',
  facebook_url: 'https://facebook.com/maisonelora',
  instagram_url: 'https://instagram.com/maisonelora',
  is_active: true,
  updated_at: new Date().toISOString(),
};

const defaultCategories: Category[] = [
  {
    id: 'cat-skincare',
    name_en: 'Skincare',
    name_bn: 'স্কিনকেয়ার',
    slug: 'skincare',
    subtitle_en: 'Face Care • Serum • Moisturizer',
    subtitle_bn: 'ফেস কেয়ার • সিরাম • ময়েশ্চারাইজার',
    image_url: '/images/categories/category-skincare-ref.jpg',
    sort_order: 1,
    is_active: true,
  },
  {
    id: 'cat-cosmetics',
    name_en: 'Cosmetics',
    name_bn: 'কসমেটিকস',
    slug: 'cosmetics',
    subtitle_en: 'Makeup • Lip Care • Beauty Essentials',
    subtitle_bn: 'মেকআপ • লিপ কেয়ার • বিউটি এসেনশিয়াল',
    image_url: '/images/categories/category-cosmetics-ref.jpg',
    sort_order: 2,
    is_active: true,
  },
  {
    id: 'cat-perfumes',
    name_en: 'Perfumes',
    name_bn: 'সুগন্ধি / পারফিউম',
    slug: 'perfumes',
    subtitle_en: 'Women • Men • Unisex Fragrance',
    subtitle_bn: 'নারী • পুরুষ • ইউনিসেক্স সুবাস',
    image_url: '/images/categories/category-perfumes-ref.jpg',
    sort_order: 3,
    is_active: true,
  },
  {
    id: 'cat-chocolates',
    name_en: 'Chocolates',
    name_bn: 'চকলেট',
    slug: 'chocolates',
    subtitle_en: 'Imported • Gift Box • Premium Treats',
    subtitle_bn: 'আমদানিকৃত • গিফট বক্স • প্রিমিয়াম ট্রিট',
    image_url: '/images/categories/category-chocolates-ref.jpg',
    sort_order: 4,
    is_active: true,
  },
];

const defaultProducts: Product[] = [
  {
    id: 'prod-to-niacinamide',
    name_en: 'The Ordinary Niacinamide 10% + Zinc 1%',
    name_bn: 'দি অর্ডিনারি নিয়াসিনামাইড ১০% + জিংক ১%',
    slug: 'the-ordinary-niacinamide-10-zinc-1',
    sku: 'SKU-TO-NIA-30',
    category_id: 'cat-skincare',
    brand_id: 'The Ordinary',
    short_description_en: 'High-strength vitamin and mineral blemish formula that reduces the appearance of skin blemishes and congestion.',
    short_description_bn: 'ত্বকের দাগ ও পোরস দূর করতে অত্যন্ত কার্যকর ভিটামিন ও মিনারেল সমৃদ্ধ লাইটওয়েট সিরাম।',
    description_en: 'Niacinamide (Vitamin B3) is indicated to reduce the appearance of skin blemishes and congestion. Supported by 1% Zinc PCA to balance visible sebum activity.',
    description_bn: 'নিয়াসিনামাইড (ভিটামিন বি৩) ত্বকের অতিরিক্ত তেল ও ব্রণের দাগ হ্রাস করে ত্বককে মসৃণ ও উজ্জ্বল করে।',
    price: 1490,
    compare_at_price: 1690,
    cost_price: 950,
    volume_weight: '30 ml',
    country_of_origin: 'Canada',
    is_new_arrival: true,
    is_best_seller: true,
    is_featured: true,
    rating: 4.9,
    review_count: 128,
    images: [
      '/images/products/prod-the-ordinary-ref.jpg',
      'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80'
    ],
    skin_type: ['All', 'Oily', 'Combination'],
    skin_concerns: ['Acne', 'Large Pores', 'Oiliness'],
    authenticity_notes: 'Imported directly from Deciem Canada authorized retail inventory.',
    ingredients_en: 'Aqua (Water), Niacinamide, Pentylene Glycol, Zinc PCA, Dimethyl Isosorbide, Tamarindus Indica Seed Gum, Xanthan gum, Isoceteth-20, Ethoxydiglycol, Phenoxyethanol, Chlorphenesin.',
    usage_instructions_en: 'Apply to entire face morning and evening before heavier creams.',
  },
  {
    id: 'prod-cosrx-snail',
    name_en: 'COSRX Advanced Snail 96 Mucin Power Essence',
    name_bn: 'কসআরএক্স অ্যাডভান্সড স্নেইল ৯৬ মুসিন এসেন্স',
    slug: 'cosrx-advanced-snail-96-mucin-essence',
    sku: 'SKU-COS-SNAIL-100',
    category_id: 'cat-skincare',
    brand_id: 'COSRX',
    short_description_en: 'Formulated with 96.3% Snail Secretion Filtrate, this essence repairs and rejuvenates dry and damaged skin.',
    short_description_bn: '৯৬.৩% স্নেইল সিক্রেশন সমৃদ্ধ ডিপ হাইড্রেশন ও গ্লোয়িং স্কিন এসেন্স।',
    description_en: 'Lightweight essence which absorbs quickly into skin to give you natural glow from inside. Creates a moisture barrier to plump, hydrate, and soothe skin.',
    description_bn: 'ত্বকের গভীর থেকে আর্দ্রতা ফিরিয়ে আনে এবং ড্যামেজড স্কিন রিপেয়ার করে প্রাকৃতিক জেল্লা প্রদান করে।',
    price: 1990,
    compare_at_price: 2250,
    cost_price: 1300,
    volume_weight: '100 ml',
    country_of_origin: 'South Korea',
    is_new_arrival: true,
    is_best_seller: true,
    is_featured: true,
    rating: 4.95,
    review_count: 214,
    images: [
      '/images/products/prod-cosrx-ref.jpg',
      'https://images.unsplash.com/photo-1608248597359-548d4c1b12b5?auto=format&fit=crop&w=800&q=80'
    ],
    skin_type: ['Dry', 'Sensitive', 'Normal', 'Combination'],
    skin_concerns: ['Dryness', 'Barrier Damage', 'Dullness'],
    authenticity_notes: 'Directly imported from official Korean wholesale export hubs.',
    ingredients_en: 'Snail Secretion Filtrate, Betaine, Butylene Glycol, 1,2-Hexanediol, Sodium Polyacrylate, Phenoxyethanol, Sodium Hyaluronate, Allantoin, Carbomer, Panthenol, Arginine.',
    usage_instructions_en: 'After cleansing and toning, apply a small amount on your entire face. Gently pat using fingertips to aid absorption.',
  },
  {
    id: 'prod-laneige-lip',
    name_en: 'LANEIGE Lip Sleeping Mask (Berry)',
    name_bn: 'লানেজ লিপ স্লিপিং মাস্ক (বেরি)',
    slug: 'laneige-lip-sleeping-mask-berry',
    sku: 'SKU-LAN-LIP-20',
    category_id: 'cat-cosmetics',
    brand_id: 'LANEIGE',
    short_description_en: 'A leave-on lip mask that delivers intense moisture and antioxidants while you sleep.',
    short_description_bn: 'রাতের বেলা ঠোঁটের রুক্ষতা দূর করে কোমল ও গোলাপি করার প্রিমিয়াম লিপ মাস্ক।',
    description_en: 'Enriched with vitamin C and antioxidants, Berry Fruit Complex offers a nutritiously sweet and fragrant blend of raspberry, strawberry, cranberry, and blueberry extracts.',
    description_bn: 'ভিটামিন সি এবং বেরি ফ্রুট কমপ্লেক্স সমৃদ্ধ যা শুষ্ক ও ফাটা ঠোঁটকে নরম, হাইড্রেটেড ও উজ্জ্বল করে।',
    price: 1650,
    compare_at_price: 1850,
    cost_price: 1050,
    volume_weight: '20 g',
    country_of_origin: 'South Korea',
    is_new_arrival: true,
    is_best_seller: true,
    is_featured: true,
    rating: 4.88,
    review_count: 97,
    images: [
      '/images/products/prod-laneige-ref.jpg',
      'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=800&q=80'
    ],
    skin_type: ['All'],
    skin_concerns: ['Dry Lips', 'Chapped Lips'],
    authenticity_notes: 'Original Amorepacific seal verified imported batch.',
    ingredients_en: 'Diisostearyl Malate, Hydrogenated Polyisobutene, Phytosteryl/Isostearyl/Cetyl/Stearyl/Behenyl Dimer Dilinoleate, Astrocaryum Murumuru Seed Butter, Shea Butter, Berry Fruit Complex.',
    usage_instructions_en: 'Before going to bed at night, apply an adequate amount on the lips using the built-in spatula. The next morning, gently wipe the lips clean with tissue or cotton pad.',
  },
  {
    id: 'prod-versace-crystal',
    name_en: 'Versace Bright Crystal Eau de Toilette',
    name_bn: 'ভারসাচ ব্রাইট ক্রিস্টাল পারফিউম',
    slug: 'versace-bright-crystal-edt-90ml',
    sku: 'SKU-VER-CRYSTAL-90',
    category_id: 'cat-perfumes',
    brand_id: 'Versace',
    short_description_en: 'A precious jewel of rare beauty characterized by a fresh, vibrant, flowery scent.',
    short_description_bn: 'ইতালিয়ান বিলাসবহুল ফ্লোরাল ও ফ্রুটি সুবাসের দীর্ঘস্থায়ী লাক্সারি সুগন্ধি।',
    description_en: 'Top notes of pomegranate, yuzu, and frosted accord. Floral heart of peony and magnolia with a sensual base of amber, acajou, and musk.',
    description_bn: 'ডালিম, ইউজু এবং পিওনি ফুলের মেলবন্ধনে তৈরি বিশ্বখ্যাত লাক্সারি পারফিউম।',
    price: 8990,
    compare_at_price: 9990,
    cost_price: 6200,
    volume_weight: '90 ml',
    country_of_origin: 'Italy',
    is_new_arrival: true,
    is_best_seller: true,
    is_featured: true,
    rating: 4.96,
    review_count: 64,
    images: [
      '/images/products/prod-versace-ref.jpg',
      'https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=800&q=80'
    ],
    fragrance_family: 'Floral Fruity Musky',
    concentration: 'EDT',
    authenticity_notes: 'EuroLux certified European duty-free origin with original batch code.',
    usage_instructions_en: 'Spray directly on pulse points: wrists, neck, and behind ears.',
  },
  {
    id: 'prod-ferrero-t24',
    name_en: 'Ferrero Rocher Premium Gold T24 Imported Chocolate Gift Box',
    name_bn: 'ফেরেরো রোশার গোল্ড টি২৪ চকলেট গিফট বক্স',
    slug: 'ferrero-rocher-gold-t24-300g',
    sku: 'SKU-FER-T24-300',
    category_id: 'cat-chocolates',
    brand_id: 'Ferrero Rocher',
    short_description_en: 'A whole hazelnut surrounded by delicious layers of crispy wafer, velvety cream, and finely chopped hazelnuts.',
    short_description_bn: 'খাস্তা ওয়েফার ও রোস্টেড হ্যাজেলনাট ক্রিমের বিলাসবহুল ইতালিয়ান চকলেট গিফট প্যাক।',
    description_en: 'A timeless classic presented in an iconic transparent gift box with golden foil wrappers.',
    description_bn: 'বিশেষ উৎসব ও প্রিয়জনকে উপহার দেওয়ার সেরা প্রিমিয়াম চকলেট বক্স।',
    price: 1850,
    compare_at_price: 2100,
    cost_price: 1200,
    volume_weight: '300 g',
    country_of_origin: 'Italy',
    is_new_arrival: true,
    is_best_seller: true,
    is_featured: true,
    rating: 4.98,
    review_count: 88,
    images: [
      'https://images.unsplash.com/photo-1549007994-cb92caebd54b?auto=format&fit=crop&w=800&q=80'
    ],
    chocolate_flavor: 'Crispy Hazelnut & Milk Chocolate',
    authenticity_notes: 'Temperature-controlled air-freighted European imported stock.',
  },
  {
    id: 'prod-lancome-genifique',
    name_en: 'Lancôme Advanced Génifique Youth Activating Concentrate',
    name_bn: 'ল্যাঙ্কোম অ্যাডভান্সড জেনিফিক কনসেন্ট্রেট',
    slug: 'lancome-advanced-genifique-concentrate',
    sku: 'SKU-LAN-GEN-50',
    category_id: 'cat-skincare',
    brand_id: 'Lancôme',
    short_description_en: 'Iconic anti-aging serum engineered with 7 pre- and probiotic fractions for fast recovery.',
    short_description_bn: 'স্কিন মাইক্রোবায়োম ও অ্যান্টি-এজিং রিকভারির জন্য বিশ্বসেরা লাক্সারি সিরাম।',
    description_en: 'Strengthens skin moisture barrier and delivers visibly smoother, more radiant skin in 7 days.',
    description_bn: 'মাত্র ৭ দিনে ত্বকের তারুণ্য ও স্বাভাবিক টানটান উজ্জ্বলতা ফিরিয়ে আনে।',
    price: 14500,
    compare_at_price: 16000,
    cost_price: 10200,
    volume_weight: '50 ml',
    country_of_origin: 'France',
    is_new_arrival: false,
    is_best_seller: true,
    is_featured: true,
    rating: 5.0,
    review_count: 42,
    images: [
      'https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=800&q=80'
    ],
    skin_type: ['All', 'Mature', 'Sensitive'],
    skin_concerns: ['Anti-aging', 'Fine Lines', 'Firmness'],
    authenticity_notes: 'Genuine French manufactured stock with authenticated batch number.',
  }
];

const defaultInventoryBatches: InventoryBatch[] = [
  {
    id: 'batch-01',
    batch_number: 'BATCH-TO-2026-A1',
    product_id: 'prod-to-niacinamide',
    supplier_id: 'EuroLux Logistics UK',
    purchase_date: '2026-01-10',
    manufacturing_date: '2025-11-01',
    expiry_date: '2028-11-01',
    initial_quantity: 50,
    available_quantity: 42,
    purchase_cost: 950,
    status: 'active',
    notes: 'Fresh batch direct shipment',
  },
  {
    id: 'batch-02',
    batch_number: 'BATCH-COS-2026-B4',
    product_id: 'prod-cosrx-snail',
    supplier_id: 'K-Beauty Direct Seoul',
    purchase_date: '2026-02-01',
    manufacturing_date: '2025-12-15',
    expiry_date: '2028-12-15',
    initial_quantity: 60,
    available_quantity: 51,
    purchase_cost: 1300,
    status: 'active',
    notes: 'Air-freighted from Incheon',
  },
  {
    id: 'batch-03',
    batch_number: 'BATCH-LAN-2026-C2',
    product_id: 'prod-laneige-lip',
    supplier_id: 'K-Beauty Direct Seoul',
    purchase_date: '2026-02-15',
    manufacturing_date: '2026-01-05',
    expiry_date: '2029-01-05',
    initial_quantity: 40,
    available_quantity: 36,
    purchase_cost: 1050,
    status: 'active',
    notes: 'Berry 20g standard',
  },
  {
    id: 'batch-04',
    batch_number: 'BATCH-VER-2026-V9',
    product_id: 'prod-versace-crystal',
    supplier_id: 'EuroLux Logistics UK',
    purchase_date: '2026-01-20',
    manufacturing_date: '2025-10-01',
    expiry_date: '2030-10-01',
    initial_quantity: 25,
    available_quantity: 21,
    purchase_cost: 6200,
    status: 'active',
    notes: 'EDT 90ml luxury stock',
  },
  {
    id: 'batch-05',
    batch_number: 'BATCH-FER-2026-F5',
    product_id: 'prod-ferrero-t24',
    supplier_id: 'ChocoGourmet Belgium',
    purchase_date: '2026-03-01',
    manufacturing_date: '2026-02-01',
    expiry_date: '2027-02-01',
    initial_quantity: 50,
    available_quantity: 39,
    purchase_cost: 1200,
    status: 'active',
    notes: 'Cool storage stored chocolate',
  },
  {
    id: 'batch-06',
    batch_number: 'BATCH-LAN-2026-G1',
    product_id: 'prod-lancome-genifique',
    supplier_id: 'EuroLux Logistics UK',
    purchase_date: '2026-01-25',
    manufacturing_date: '2025-09-01',
    expiry_date: '2028-09-01',
    initial_quantity: 15,
    available_quantity: 12,
    purchase_cost: 10200,
    status: 'active',
    notes: 'Lancôme youth concentrate',
  }
];

const defaultCoupons: Coupon[] = [
  {
    id: 'c-01',
    code: 'ELORA10',
    discount_type: 'percentage',
    discount_value: 10,
    min_order_amount: 2000,
    is_active: true,
  },
  {
    id: 'c-02',
    code: 'WELCOME200',
    discount_type: 'fixed_amount',
    discount_value: 200,
    min_order_amount: 1500,
    is_active: true,
  },
  {
    id: 'c-03',
    code: 'FREESHIP',
    discount_type: 'free_shipping',
    discount_value: 0,
    min_order_amount: 1000,
    is_active: true,
  }
];

interface EloraState {
  language: 'en' | 'bn';
  setLanguage: (lang: 'en' | 'bn') => void;
  
  // Brand Identity & Dynamic Logo
  brandSettings: BrandSettings;
  updateBrandSettings: (settings: Partial<BrandSettings>) => void;
  brandAssets: BrandAsset[];
  uploadBrandAsset: (asset: Omit<BrandAsset, 'id' | 'created_at' | 'updated_at'>) => BrandAsset;
  setActiveLogo: (assetId: string, type: AssetType) => void;
  resetLogoToDefault: (type: AssetType) => void;
  restorePreviousLogo: (type: AssetType) => void;
  deleteBrandAsset: (assetId: string) => void;
  getActiveLogo: (type: AssetType) => BrandAsset;
  
  // Catalog
  categories: Category[];
  products: Product[];
  addProduct: (product: Omit<Product, 'id'>) => Product;
  updateProduct: (id: string, product: Partial<Product>) => void;
  deleteProduct: (id: string) => void;
  
  // Inventory & FEFO Batches
  inventoryBatches: InventoryBatch[];
  addInventoryBatch: (batch: Omit<InventoryBatch, 'id'>) => InventoryBatch;
  updateBatchQuantity: (batchId: string, qtyDelta: number) => void;
  
  // Cart & Drawer
  cart: CartItem[];
  isCartOpen: boolean;
  setCartOpen: (open: boolean) => void;
  addToCart: (product: Product, quantity?: number, variantId?: string) => void;
  updateCartQuantity: (productId: string, quantity: number) => void;
  removeFromCart: (productId: string) => void;
  clearCart: () => void;
  
  // Wishlist
  wishlist: Product[];
  toggleWishlist: (product: Product) => void;
  isInWishlist: (productId: string) => boolean;
  
  // Checkout & Coupons
  coupons: Coupon[];
  appliedCoupon: Coupon | null;
  applyCoupon: (code: string, subtotal: number) => { success: boolean; message: string };
  removeCoupon: () => void;
  
  // Orders & Payment
  orders: Order[];
  createOrder: (orderInput: Omit<Order, 'id' | 'order_number' | 'created_at' | 'updated_at'>) => Order;
  updateOrderStatus: (orderId: string, status: OrderStatus) => void;
  updatePaymentStatus: (orderId: string, status: PaymentStatus, reason?: string) => void;
  
  // Search
  searchQuery: string;
  setSearchQuery: (query: string) => void;
}

export const useEloraStore = create<EloraState>()(
  persist(
    (set, get) => ({
      language: 'en',
      setLanguage: (lang) => set({ language: lang }),
      
      // Brand Settings
      brandSettings: defaultBrandSettings,
      updateBrandSettings: (newSettings) =>
        set((state) => ({
          brandSettings: { ...state.brandSettings, ...newSettings, updated_at: new Date().toISOString() },
        })),
        
      // Brand Assets & Dynamic Logos
      brandAssets: defaultBrandAssets,
      uploadBrandAsset: (assetData) => {
        const newAsset: BrandAsset = {
          ...assetData,
          id: `asset-${Date.now()}-${Math.random().toString(36).substr(2, 5)}`,
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        };
        set((state) => {
          // Deactivate existing active asset of same type if this is active
          const updatedAssets = state.brandAssets.map((a) =>
            a.asset_type === newAsset.asset_type && newAsset.is_active ? { ...a, is_active: false } : a
          );
          return { brandAssets: [newAsset, ...updatedAssets] };
        });
        return newAsset;
      },
      setActiveLogo: (assetId, type) => {
        set((state) => ({
          brandAssets: state.brandAssets.map((asset) => {
            if (asset.asset_type === type) {
              return { ...asset, is_active: asset.id === assetId };
            }
            return asset;
          }),
        }));
      },
      resetLogoToDefault: (type) => {
        const defaultAsset = defaultBrandAssets.find((a) => a.asset_type === type);
        if (!defaultAsset) return;
        set((state) => ({
          brandAssets: state.brandAssets.map((asset) => {
            if (asset.asset_type === type) {
              return { ...asset, is_active: asset.id === defaultAsset.id };
            }
            return asset;
          }),
        }));
      },
      restorePreviousLogo: (type) => {
        set((state) => {
          const typeAssets = state.brandAssets.filter((a) => a.asset_type === type);
          if (typeAssets.length < 2) return state;
          // Find first inactive asset to restore
          const inactive = typeAssets.find((a) => !a.is_active);
          if (!inactive) return state;
          return {
            brandAssets: state.brandAssets.map((a) => {
              if (a.asset_type === type) {
                return { ...a, is_active: a.id === inactive.id };
              }
              return a;
            }),
          };
        });
      },
      deleteBrandAsset: (assetId) => {
        set((state) => ({
          brandAssets: state.brandAssets.filter((a) => a.id !== assetId || a.is_active),
        }));
      },
      getActiveLogo: (type) => {
        const state = get();
        const active = state.brandAssets.find((a) => a.asset_type === type && a.is_active);
        if (active) return active;
        // Fallback to primary active
        const primary = state.brandAssets.find((a) => a.asset_type === 'primary' && a.is_active);
        if (primary) return primary;
        // Fallback to hardcoded default
        return defaultBrandAssets[0];
      },
      
      // Catalog
      categories: defaultCategories,
      products: defaultProducts,
      addProduct: (prod) => {
        const newProduct: Product = {
          ...prod,
          id: `prod-${Date.now()}`,
        };
        set((state) => ({ products: [newProduct, ...state.products] }));
        return newProduct;
      },
      updateProduct: (id, updated) => {
        set((state) => ({
          products: state.products.map((p) => (p.id === id ? { ...p, ...updated } : p)),
        }));
      },
      deleteProduct: (id) => {
        set((state) => ({
          products: state.products.filter((p) => p.id !== id),
        }));
      },
      
      // Inventory & Batches
      inventoryBatches: defaultInventoryBatches,
      addInventoryBatch: (batchData) => {
        const newBatch: InventoryBatch = {
          ...batchData,
          id: `batch-${Date.now()}`,
        };
        set((state) => ({ inventoryBatches: [newBatch, ...state.inventoryBatches] }));
        return newBatch;
      },
      updateBatchQuantity: (batchId, delta) => {
        set((state) => ({
          inventoryBatches: state.inventoryBatches.map((b) =>
            b.id === batchId ? { ...b, available_quantity: Math.max(0, b.available_quantity + delta) } : b
          ),
        }));
      },
      
      // Cart
      cart: [],
      isCartOpen: false,
      setCartOpen: (open) => set({ isCartOpen: open }),
      addToCart: (product, qty = 1, variantId) => {
        set((state) => {
          const existingIndex = state.cart.findIndex(
            (item) => item.product.id === product.id && item.variantId === variantId
          );
          if (existingIndex > -1) {
            const updated = [...state.cart];
            updated[existingIndex].quantity += qty;
            return { cart: updated, isCartOpen: true };
          }
          return { cart: [...state.cart, { product, quantity: qty, variantId }], isCartOpen: true };
        });
      },
      updateCartQuantity: (productId, quantity) => {
        set((state) => {
          if (quantity <= 0) {
            return { cart: state.cart.filter((item) => item.product.id !== productId) };
          }
          return {
            cart: state.cart.map((item) =>
              item.product.id === productId ? { ...item, quantity } : item
            ),
          };
        });
      },
      removeFromCart: (productId) => {
        set((state) => ({
          cart: state.cart.filter((item) => item.product.id !== productId),
        }));
      },
      clearCart: () => set({ cart: [], appliedCoupon: null }),
      
      // Wishlist
      wishlist: [],
      toggleWishlist: (product) => {
        set((state) => {
          const exists = state.wishlist.some((p) => p.id === product.id);
          if (exists) {
            return { wishlist: state.wishlist.filter((p) => p.id !== product.id) };
          }
          return { wishlist: [...state.wishlist, product] };
        });
      },
      isInWishlist: (productId) => {
        return get().wishlist.some((p) => p.id === productId);
      },
      
      // Coupons
      coupons: defaultCoupons,
      appliedCoupon: null,
      applyCoupon: (code, subtotal) => {
        const found = get().coupons.find(
          (c) => c.code.toUpperCase() === code.trim().toUpperCase() && c.is_active
        );
        if (!found) {
          return { success: false, message: 'Invalid coupon code.' };
        }
        if (found.min_order_amount && subtotal < found.min_order_amount) {
          return {
            success: false,
            message: `Minimum order amount of ৳${found.min_order_amount.toLocaleString()} required for this coupon.`,
          };
        }
        set({ appliedCoupon: found });
        return { success: true, message: `Coupon ${found.code} applied successfully!` };
      },
      removeCoupon: () => set({ appliedCoupon: null }),
      
      // Orders
      orders: [
        {
          id: 'ord-1001',
          order_number: 'ME-2026-1001',
          customer_name: 'Tasnim Rahman',
          customer_phone: '01711223344',
          customer_email: 'tasnim@example.com',
          shipping_division: 'Dhaka',
          shipping_district: 'Dhaka',
          shipping_upazila: 'Gulshan',
          shipping_address: 'House 14, Road 28, Gulshan 1',
          subtotal: 3480,
          discount_amount: 348,
          coupon_code: 'ELORA10',
          shipping_cost: 0,
          total_amount: 3132,
          order_status: 'processing',
          payment_status: 'verified',
          payment_method: 'bkash',
          transaction_id: 'BK928374189',
          sender_phone: '01711223344',
          courier_name: 'Pathao Courier',
          tracking_number: 'PTH-90281-BD',
          created_at: new Date(Date.now() - 3600000 * 24).toISOString(),
          updated_at: new Date().toISOString(),
          items: [
            {
              id: 'item-1',
              order_id: 'ord-1001',
              product_id: 'prod-to-niacinamide',
              product_name: 'The Ordinary Niacinamide 10% + Zinc 1%',
              product_sku: 'SKU-TO-NIA-30',
              unit_price: 1490,
              quantity: 1,
              total_price: 1490,
              image_url: 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80',
            },
            {
              id: 'item-2',
              order_id: 'ord-1001',
              product_id: 'prod-cosrx-snail',
              product_name: 'COSRX Advanced Snail 96 Mucin Power Essence',
              product_sku: 'SKU-COS-SNAIL-100',
              unit_price: 1990,
              quantity: 1,
              total_price: 1990,
              image_url: 'https://images.unsplash.com/photo-1608248597359-548d4c1b12b5?auto=format&fit=crop&w=800&q=80',
            }
          ]
        }
      ],
      createOrder: (orderInput) => {
        const orderNumber = `ME-2026-${Math.floor(1000 + Math.random() * 9000)}`;
        const newOrder: Order = {
          ...orderInput,
          id: `ord-${Date.now()}`,
          order_number: orderNumber,
          created_at: new Date().toISOString(),
          updated_at: new Date().toISOString(),
        };

        // FEFO Inventory Allocation: Deduct items from earliest expiring batch
        const currentBatches = [...get().inventoryBatches];
        newOrder.items.forEach((item) => {
          // Find matching batches sorted by expiry_date ASC (FEFO)
          const eligibleBatches = currentBatches
            .filter((b) => b.product_id === item.product_id && b.available_quantity > 0 && b.status === 'active')
            .sort((a, b) => new Date(a.expiry_date).getTime() - new Date(b.expiry_date).getTime());

          let remainingQtyToAllocate = item.quantity;
          for (const b of eligibleBatches) {
            if (remainingQtyToAllocate <= 0) break;
            const take = Math.min(b.available_quantity, remainingQtyToAllocate);
            b.available_quantity -= take;
            remainingQtyToAllocate -= take;
            if (b.available_quantity === 0) {
              b.status = 'depleted';
            }
          }
        });

        set((state) => ({
          orders: [newOrder, ...state.orders],
          inventoryBatches: currentBatches,
        }));

        return newOrder;
      },
      updateOrderStatus: (orderId, status) => {
        set((state) => ({
          orders: state.orders.map((o) =>
            o.id === orderId ? { ...o, order_status: status, updated_at: new Date().toISOString() } : o
          ),
        }));
      },
      updatePaymentStatus: (orderId, status, reason) => {
        set((state) => ({
          orders: state.orders.map((o) =>
            o.id === orderId
              ? {
                  ...o,
                  payment_status: status,
                  notes: reason ? `${o.notes || ''} [Payment note: ${reason}]` : o.notes,
                  updated_at: new Date().toISOString(),
                }
              : o
          ),
        }));
      },
      
      // Search
      searchQuery: '',
      setSearchQuery: (query) => set({ searchQuery: query }),
    }),
    {
      name: 'maison-elora-storage-v3',
      storage: createJSONStorage(() => localStorage),
      partialize: (state) => ({
        language: state.language,
        brandSettings: state.brandSettings,
        brandAssets: state.brandAssets,
        cart: state.cart,
        wishlist: state.wishlist,
        orders: state.orders,
        inventoryBatches: state.inventoryBatches,
      }),
    }
  )
);
