export type AssetType =
  | 'primary'
  | 'compact'
  | 'mobile'
  | 'footer'
  | 'admin'
  | 'favicon'
  | 'social'
  | 'email'
  | 'banner';

export interface BrandAsset {
  id: string;
  asset_type: AssetType;
  asset_name: string;
  storage_path: string;
  public_url_or_path: string;
  alt_text?: string;
  width?: number;
  height?: number;
  mime_type?: string;
  file_size?: number;
  is_active: boolean;
  uploaded_by?: string;
  created_at: string;
  updated_at: string;
}

export interface BrandSettings {
  id: string;
  brand_name: string;
  tagline: string;
  primary_color: string;
  secondary_color: string;
  accent_color: string;
  rose_nude_color: string;
  charcoal_color: string;
  header_announcement_en: string;
  header_announcement_bn: string;
  contact_phone: string;
  contact_email: string;
  whatsapp_number: string;
  business_address: string;
  facebook_url: string;
  instagram_url: string;
  is_active: boolean;
  updated_at: string;
}

export interface Category {
  id: string;
  name_en: string;
  name_bn: string;
  slug: string;
  subtitle_en?: string;
  subtitle_bn?: string;
  image_url?: string;
  sort_order: number;
  is_active: boolean;
}

export interface Brand {
  id: string;
  name: string;
  slug: string;
  country_of_origin?: string;
  logo_url?: string;
  is_featured: boolean;
}

export interface Product {
  id: string;
  name_en: string;
  name_bn: string;
  slug: string;
  sku: string;
  category_id: string;
  brand_id?: string;
  short_description_en?: string;
  short_description_bn?: string;
  description_en?: string;
  description_bn?: string;
  price: number;
  compare_at_price?: number;
  cost_price?: number;
  volume_weight?: string;
  country_of_origin?: string;
  is_new_arrival: boolean;
  is_best_seller: boolean;
  is_featured: boolean;
  rating: number;
  review_count: number;
  images: string[];
  
  // Specific attributes
  skin_type?: string[];
  skin_concerns?: string[];
  fragrance_family?: string;
  concentration?: string;
  chocolate_flavor?: string;
  authenticity_notes?: string;
  ingredients_en?: string;
  ingredients_bn?: string;
  usage_instructions_en?: string;
  usage_instructions_bn?: string;
  
  // Joins
  category?: Category;
  brand?: Brand;
}

export interface InventoryBatch {
  id: string;
  batch_number: string;
  product_id: string;
  variant_id?: string;
  supplier_id?: string;
  purchase_date: string;
  manufacturing_date?: string;
  expiry_date: string;
  initial_quantity: number;
  available_quantity: number;
  purchase_cost: number;
  status: 'active' | 'near_expiry' | 'expired' | 'depleted' | 'quarantine';
  notes?: string;
  product?: Product;
}

export interface CartItem {
  product: Product;
  variantId?: string;
  quantity: number;
}

export type OrderStatus =
  | 'pending'
  | 'confirmed'
  | 'processing'
  | 'packed'
  | 'shipped'
  | 'out_for_delivery'
  | 'delivered'
  | 'cancelled'
  | 'return_requested'
  | 'returned';

export type PaymentStatus =
  | 'pending'
  | 'submitted'
  | 'under_review'
  | 'verified'
  | 'rejected'
  | 'refunded';

export type PaymentMethod = 'cod' | 'bkash' | 'nagad' | 'rocket' | 'card';

export interface OrderItem {
  id: string;
  order_id: string;
  product_id: string;
  product_name: string;
  product_sku: string;
  unit_price: number;
  quantity: number;
  total_price: number;
  batch_allocated_id?: string;
  image_url?: string;
}

export interface Order {
  id: string;
  order_number: string;
  customer_name: string;
  customer_email?: string;
  customer_phone: string;
  shipping_division: string;
  shipping_district: string;
  shipping_upazila?: string;
  shipping_address: string;
  delivery_instructions?: string;
  subtotal: number;
  discount_amount: number;
  coupon_code?: string;
  shipping_cost: number;
  total_amount: number;
  order_status: OrderStatus;
  payment_status: PaymentStatus;
  payment_method: PaymentMethod;
  transaction_id?: string;
  sender_phone?: string;
  courier_name?: string;
  tracking_number?: string;
  notes?: string;
  items: OrderItem[];
  created_at: string;
  updated_at: string;
}

export interface Coupon {
  id: string;
  code: string;
  discount_type: 'percentage' | 'fixed_amount' | 'free_shipping';
  discount_value: number;
  min_order_amount?: number;
  is_active: boolean;
}
