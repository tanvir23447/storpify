'use client';

import React, { useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { SlideCart } from '@/components/cart/SlideCart';
import { Footer } from '@/components/storefront/Footer';
import { ProductCard } from '@/components/products/ProductCard';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import {
  Star,
  ShieldCheck,
  Truck,
  RefreshCw,
  Heart,
  ShoppingBag,
  Zap,
  Calendar,
  CheckCircle2,
  Info,
  Clock,
  ChevronRight,
  Plus,
  Minus
} from 'lucide-react';

export default function ProductDetailPage() {
  const params = useParams();
  const router = useRouter();
  const slug = params?.slug as string;

  const language = useEloraStore((state) => state.language);
  const products = useEloraStore((state) => state.products);
  const inventoryBatches = useEloraStore((state) => state.inventoryBatches);
  const addToCart = useEloraStore((state) => state.addToCart);
  const toggleWishlist = useEloraStore((state) => state.toggleWishlist);
  const isInWishlist = useEloraStore((state) => state.isInWishlist);

  const [quantity, setQuantity] = useState(1);
  const [selectedImageIdx, setSelectedImageIdx] = useState(0);
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);
  const [activeTab, setActiveTab] = useState<'details' | 'ingredients' | 'how_to_use' | 'authenticity'>('details');

  const t = language === 'bn' ? bn : en;

  // Find product
  const product = products.find((p) => p.slug === slug) || products[0];
  const inWishlist = isInWishlist(product.id);

  // Find active FEFO batch for this product
  const activeBatches = inventoryBatches
    .filter((b) => b.product_id === product.id && b.available_quantity > 0)
    .sort((a, b) => new Date(a.expiry_date).getTime() - new Date(b.expiry_date).getTime());

  const currentBatch = activeBatches[0];
  const totalStock = activeBatches.reduce((acc, b) => acc + b.available_quantity, 0);

  // Discount percentage
  const discountPercent = product.compare_at_price && product.compare_at_price > product.price
    ? Math.round(((product.compare_at_price - product.price) / product.compare_at_price) * 100)
    : 0;

  // Related products
  const related = products.filter((p) => p.id !== product.id).slice(0, 4);

  const handleAddToCart = () => {
    addToCart(product, quantity);
  };

  const handleBuyNow = () => {
    addToCart(product, quantity);
    router.push('/checkout');
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header onToggleMobileNav={() => setIsMobileNavOpen(true)} />
      <NavigationBar
        isMobileOpen={isMobileNavOpen}
        onCloseMobile={() => setIsMobileNavOpen(false)}
      />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {/* Breadcrumb */}
        <div className="flex items-center gap-1.5 text-xs text-[#7A7072] mb-6">
          <Link href="/" className="hover:text-elora-berry">Home</Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <Link href="/shop" className="hover:text-elora-berry">Shop</Link>
          <ChevronRight className="w-3.5 h-3.5" />
          <span className="text-elora-charcoal font-medium truncate max-w-xs sm:max-w-md">
            {language === 'bn' ? product.name_bn : product.name_en}
          </span>
        </div>

        {/* Product Hero: Gallery + Info */}
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 bg-white p-6 sm:p-10 rounded-3xl border border-[#F0D8D9] shadow-sm mb-12">
          
          {/* Gallery (Col 6) */}
          <div className="lg:col-span-6 space-y-4">
            <div className="relative w-full aspect-square rounded-2xl overflow-hidden bg-[#FFF9F2] border border-[#EEDAD9] p-6 flex items-center justify-center">
              <Image
                src={product.images[selectedImageIdx] || product.images[0]}
                alt={product.name_en}
                fill
                className="object-contain p-4"
                priority
              />
              {discountPercent > 0 && (
                <span className="absolute top-4 left-4 px-3 py-1 rounded-full bg-[#B83B5E] text-white text-xs font-bold uppercase shadow-sm">
                  -{discountPercent}% OFF
                </span>
              )}
            </div>

            {/* Thumbnails */}
            {product.images.length > 1 && (
              <div className="flex gap-3">
                {product.images.map((img, idx) => (
                  <button
                    key={idx}
                    onClick={() => setSelectedImageIdx(idx)}
                    className={`relative w-20 h-20 rounded-xl overflow-hidden border-2 transition-all p-1 bg-[#FFF9F2] ${
                      selectedImageIdx === idx ? 'border-[#B83B5E] shadow-sm' : 'border-[#EEDAD9] hover:border-[#C68B91]'
                    }`}
                  >
                    <Image src={img} alt="Thumbnail" fill className="object-contain p-1" />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Product Details (Col 6) */}
          <div className="lg:col-span-6 space-y-5">
            <div>
              <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-widest block mb-1">
                {product.brand_id || 'Maison Elora'}
              </span>
              <h1 className="font-serif text-2xl sm:text-3xl lg:text-4xl font-semibold text-elora-charcoal leading-snug">
                {language === 'bn' ? product.name_bn : product.name_en}
              </h1>

              {/* Rating & Reviews */}
              <div className="flex items-center gap-3 mt-3">
                <div className="flex text-[#C9A45C]">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} className="w-4 h-4 fill-current" />
                  ))}
                </div>
                <span className="text-xs font-bold text-elora-charcoal">
                  {product.rating}
                </span>
                <span className="text-xs text-[#7A7072]">
                  ({product.review_count} {language === 'bn' ? 'রিভিউ' : 'verified reviews'})
                </span>
              </div>
            </div>

            {/* Price Box */}
            <div className="flex items-baseline gap-3 p-4 rounded-2xl bg-[#FDF5F5] border border-[#F0D8D9]">
              <span className="text-2xl sm:text-3xl font-bold text-[#B83B5E]">
                ৳{product.price.toLocaleString()}
              </span>
              {product.compare_at_price && product.compare_at_price > product.price && (
                <span className="text-sm sm:text-base text-[#7A7072] line-through">
                  ৳{product.compare_at_price.toLocaleString()}
                </span>
              )}
              {product.volume_weight && (
                <span className="text-xs text-[#7A7072] ml-auto font-medium">
                  {product.volume_weight}
                </span>
              )}
            </div>

            {/* Stock & Batch FEFO Verification Box */}
            <div className="p-3.5 rounded-xl bg-white border border-[#EEDAD9] space-y-2 text-xs">
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-1.5 text-emerald-700 font-semibold">
                  <CheckCircle2 className="w-4 h-4" />
                  <span>
                    {totalStock > 0
                      ? (language === 'bn' ? `স্টকে বিদ্যমান (${totalStock} টি)` : `In Stock & Ready to Ship (${totalStock} available)`)
                      : (language === 'bn' ? 'স্টক শেষ' : 'Out of Stock')}
                  </span>
                </div>
                <span className="text-[#7A7072]">SKU: {product.sku}</span>
              </div>

              {currentBatch && (
                <div className="grid grid-cols-2 gap-2 pt-2 border-t border-[#F0D8D9] text-[11px] text-[#554B4E]">
                  <div>
                    <span className="text-[#7A7072] block">Verified Batch No:</span>
                    <span className="font-mono font-bold text-elora-charcoal">{currentBatch.batch_number}</span>
                  </div>
                  <div>
                    <span className="text-[#7A7072] block">FEFO Expiry Date:</span>
                    <span className="font-bold text-[#B83B5E]">{currentBatch.expiry_date}</span>
                  </div>
                </div>
              )}
            </div>

            {/* Short Description */}
            <p className="text-xs sm:text-sm text-[#554B4E] leading-relaxed">
              {language === 'bn' ? product.short_description_bn : product.short_description_en}
            </p>

            {/* Quantity Selector & Action Buttons */}
            <div className="space-y-3 pt-2">
              <div className="flex items-center gap-4">
                <div className="flex items-center border border-[#EEDAD9] rounded-xl bg-[#FFF9F2] p-1">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="p-2 text-elora-charcoal hover:bg-[#FDF2F3] rounded-lg"
                    aria-label="Decrease quantity"
                  >
                    <Minus className="w-4 h-4" />
                  </button>
                  <span className="px-4 font-bold text-sm text-elora-charcoal min-w-[32px] text-center">
                    {quantity}
                  </span>
                  <button
                    onClick={() => setQuantity(quantity + 1)}
                    className="p-2 text-elora-charcoal hover:bg-[#FDF2F3] rounded-lg"
                    aria-label="Increase quantity"
                  >
                    <Plus className="w-4 h-4" />
                  </button>
                </div>

                {/* Add to Cart */}
                <button
                  onClick={handleAddToCart}
                  className="flex-1 py-3.5 px-6 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md active:scale-98"
                >
                  <ShoppingBag className="w-4 h-4" />
                  <span>{t.products.add_to_cart}</span>
                </button>

                {/* Wishlist Button */}
                <button
                  onClick={() => toggleWishlist(product)}
                  className="p-3.5 rounded-full border border-[#EEDAD9] bg-white hover:bg-[#FDF2F3] text-elora-charcoal hover:text-[#B83B5E] transition-colors"
                  aria-label="Wishlist"
                >
                  <Heart className={`w-5 h-5 ${inWishlist ? 'fill-[#B83B5E] text-[#B83B5E]' : ''}`} />
                </button>
              </div>

              {/* Buy Now Direct Button */}
              <button
                onClick={handleBuyNow}
                className="w-full py-3.5 px-6 rounded-full bg-[#292426] hover:bg-black text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md active:scale-98"
              >
                <Zap className="w-4 h-4 text-[#C9A45C]" />
                <span>{t.products.buy_now}</span>
              </button>
            </div>

            {/* Trust Micro-Badges */}
            <div className="grid grid-cols-3 gap-2 pt-4 border-t border-[#F0D8D9] text-center">
              <div className="p-2 rounded-xl bg-[#FFF9F2] space-y-1">
                <ShieldCheck className="w-4 h-4 text-[#C9A45C] mx-auto" />
                <span className="text-[10px] sm:text-[11px] font-semibold text-elora-charcoal block">100% Authentic</span>
              </div>
              <div className="p-2 rounded-xl bg-[#FFF9F2] space-y-1">
                <Truck className="w-4 h-4 text-[#B83B5E] mx-auto" />
                <span className="text-[10px] sm:text-[11px] font-semibold text-elora-charcoal block">Fast BD Delivery</span>
              </div>
              <div className="p-2 rounded-xl bg-[#FFF9F2] space-y-1">
                <RefreshCw className="w-4 h-4 text-[#C9A45C] mx-auto" />
                <span className="text-[10px] sm:text-[11px] font-semibold text-elora-charcoal block">Easy Exchange</span>
              </div>
            </div>
          </div>
        </div>

        {/* Detailed Tabs: Description, Ingredients, How to Use, Authenticity */}
        <div className="bg-white p-6 sm:p-10 rounded-3xl border border-[#F0D8D9] shadow-sm mb-14">
          <div className="flex flex-wrap gap-2 sm:gap-6 border-b border-[#EEDAD9] pb-4 mb-6">
            {[
              { id: 'details', label: language === 'bn' ? 'পণ্যের বিবরণ' : 'Description & Benefits' },
              { id: 'ingredients', label: language === 'bn' ? 'উপাদানসমূহ' : 'Key Ingredients' },
              { id: 'how_to_use', label: language === 'bn' ? 'ব্যবহারবিধি' : 'How to Use' },
              { id: 'authenticity', label: language === 'bn' ? 'অথেনটিসিটি যাচাই' : 'Authenticity & Sourcing' },
            ].map((tab) => (
              <button
                key={tab.id}
                onClick={() => setActiveTab(tab.id as any)}
                className={`pb-2 text-xs sm:text-sm font-semibold transition-colors border-b-2 ${
                  activeTab === tab.id
                    ? 'border-[#B83B5E] text-[#B83B5E]'
                    : 'border-transparent text-[#7A7072] hover:text-elora-charcoal'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </div>

          <div className="text-xs sm:text-sm text-[#4A4043] leading-relaxed space-y-4">
            {activeTab === 'details' && (
              <div className="space-y-4">
                <p>{language === 'bn' ? product.description_bn : product.description_en}</p>
                {product.skin_type && (
                  <div className="flex items-center gap-2 pt-2">
                    <span className="font-semibold text-elora-charcoal">Recommended Skin Type:</span>
                    <span className="text-[#B83B5E]">{product.skin_type.join(', ')}</span>
                  </div>
                )}
                {product.country_of_origin && (
                  <div className="flex items-center gap-2">
                    <span className="font-semibold text-elora-charcoal">Country of Origin:</span>
                    <span>{product.country_of_origin}</span>
                  </div>
                )}
              </div>
            )}

            {activeTab === 'ingredients' && (
              <div className="space-y-3">
                <h4 className="font-serif font-bold text-elora-charcoal text-base">Full Ingredients List:</h4>
                <p className="bg-[#FFF9F2] p-4 rounded-xl border border-[#EEDAD9] text-xs font-mono text-[#554B4E]">
                  {product.ingredients_en || 'Standard certified cosmetic active ingredients. Free from harmful parabens and artificial fragrance where indicated.'}
                </p>
              </div>
            )}

            {activeTab === 'how_to_use' && (
              <div className="space-y-3">
                <h4 className="font-serif font-bold text-elora-charcoal text-base">Application Guidance:</h4>
                <p className="bg-[#FFF9F2] p-4 rounded-xl border border-[#EEDAD9] text-xs sm:text-sm">
                  {product.usage_instructions_en || 'Apply 2-3 drops to clean fingertips and smooth gently across skin until fully absorbed. For best results, follow with your favorite moisturizer.'}
                </p>
              </div>
            )}

            {activeTab === 'authenticity' && (
              <div className="space-y-3 bg-[#FDF2F3] p-5 rounded-2xl border border-[#F0D8D9]">
                <div className="flex items-center gap-2 text-[#C9A45C] font-bold">
                  <ShieldCheck className="w-5 h-5" />
                  <span>Maison Elora Official Authenticity Promise</span>
                </div>
                <p className="text-xs sm:text-sm text-[#554B4E]">
                  {product.authenticity_notes || 'All our items are procured through verified authorized distributors in Europe, the United States, Korea, and the United Kingdom. Batch codes can be verified on checkfresh.com and official brand verification portals.'}
                </p>
              </div>
            )}
          </div>
        </div>

        {/* Related Products */}
        <div className="space-y-6">
          <h3 className="font-serif text-2xl font-semibold text-elora-charcoal">
            {t.products.related_products}
          </h3>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
            {related.map((p) => (
              <ProductCard key={p.id} product={p} />
            ))}
          </div>
        </div>
      </main>

      <SlideCart />
      <Footer />
    </div>
  );
}
