'use client';

import React, { useState, useMemo, Suspense } from 'react';
import { useSearchParams } from 'next/navigation';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { ProductCard } from '@/components/products/ProductCard';
import { SlideCart } from '@/components/cart/SlideCart';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { Filter, SlidersHorizontal, ArrowUpDown, X } from 'lucide-react';

function ShopContent() {
  const searchParams = useSearchParams();
  const language = useEloraStore((state) => state.language);
  const products = useEloraStore((state) => state.products);
  const categories = useEloraStore((state) => state.categories);
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);
  const [isFilterDrawerOpen, setIsFilterDrawerOpen] = useState(false);

  // Filters state
  const initialCategory = searchParams.get('category') || 'all';
  const initialFilter = searchParams.get('filter') || 'all';
  const initialSearch = searchParams.get('search') || '';

  const [selectedCategory, setSelectedCategory] = useState<string>(initialCategory);
  const [selectedBrand, setSelectedBrand] = useState<string>('all');
  const [priceMax, setPriceMax] = useState<number>(20000);
  const [sortBy, setSortBy] = useState<string>('featured');
  const [selectedSkinType, setSelectedSkinType] = useState<string>('all');

  const t = language === 'bn' ? bn : en;

  // Extract unique brands
  const brands = useMemo(() => {
    const list = new Set<string>();
    products.forEach((p) => {
      if (p.brand_id) list.add(p.brand_id);
    });
    return Array.from(list);
  }, [products]);

  // Filtered & sorted products
  const filteredProducts = useMemo(() => {
    return products
      .filter((p) => {
        // Search filter
        if (initialSearch) {
          const q = initialSearch.toLowerCase();
          const matchName = p.name_en.toLowerCase().includes(q) || p.name_bn.toLowerCase().includes(q);
          const matchBrand = p.brand_id?.toLowerCase().includes(q);
          if (!matchName && !matchBrand) return false;
        }

        // Category filter
        if (selectedCategory !== 'all') {
          const cat = categories.find((c) => c.slug === selectedCategory);
          if (cat && p.category_id !== cat.id) return false;
        }

        // Special query filter
        if (initialFilter === 'new' && !p.is_new_arrival) return false;
        if (initialFilter === 'best' && !p.is_best_seller) return false;
        if (initialFilter === 'offers' && (!p.compare_at_price || p.compare_at_price <= p.price)) return false;

        // Brand filter
        if (selectedBrand !== 'all' && p.brand_id !== selectedBrand) return false;

        // Skin type filter
        if (selectedSkinType !== 'all') {
          if (!p.skin_type || !p.skin_type.includes(selectedSkinType)) return false;
        }

        // Price filter
        if (p.price > priceMax) return false;

        return true;
      })
      .sort((a, b) => {
        if (sortBy === 'price-low') return a.price - b.price;
        if (sortBy === 'price-high') return b.price - a.price;
        if (sortBy === 'rating') return b.rating - a.rating;
        if (sortBy === 'newest') return (b.is_new_arrival ? 1 : 0) - (a.is_new_arrival ? 1 : 0);
        return 0; // default / featured
      });
  }, [products, categories, initialSearch, selectedCategory, initialFilter, selectedBrand, selectedSkinType, priceMax, sortBy]);

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header onToggleMobileNav={() => setIsMobileNavOpen(true)} />
      <NavigationBar
        isMobileOpen={isMobileNavOpen}
        onCloseMobile={() => setIsMobileNavOpen(false)}
      />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        {/* Page Breadcrumb & Title */}
        <div className="mb-6">
          <h1 className="font-serif text-2xl sm:text-4xl font-semibold text-elora-charcoal">
            {language === 'bn' ? 'সকল লাক্সারি পণ্য সংগ্রহ' : 'Luxury Beauty & Care Vault'}
          </h1>
          <p className="text-xs sm:text-sm text-[#7A7072] mt-1">
            {language === 'bn'
              ? `মোট ${filteredProducts.length}টি পণ্য পাওয়া গেছে`
              : `Showing ${filteredProducts.length} premium imported beauty products`}
          </p>
        </div>

        {/* Top Controls: Mobile Filter Button & Sorting */}
        <div className="flex items-center justify-between gap-4 pb-6 border-b border-[#EEDAD9] mb-8">
          <button
            onClick={() => setIsFilterDrawerOpen(true)}
            className="lg:hidden flex items-center gap-2 px-4 py-2 rounded-full border border-[#EEDAD9] bg-white text-xs font-semibold text-elora-charcoal shadow-xs"
          >
            <SlidersHorizontal className="w-4 h-4 text-[#B83B5E]" />
            <span>{language === 'bn' ? 'ফিল্টার' : 'Filters'}</span>
          </button>

          <div className="flex items-center gap-2 ml-auto text-xs">
            <span className="hidden sm:inline text-[#7A7072]">
              {language === 'bn' ? 'সাজান:' : 'Sort By:'}
            </span>
            <select
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value)}
              className="bg-white border border-[#EEDAD9] rounded-lg px-3 py-2 text-xs text-elora-charcoal outline-none focus:border-[#B83B5E]"
            >
              <option value="featured">{language === 'bn' ? 'ফিচার্ড' : 'Featured'}</option>
              <option value="newest">{language === 'bn' ? 'নতুন কালেকশন' : 'Newest'}</option>
              <option value="price-low">{language === 'bn' ? 'মূল্য: কম থেকে বেশি' : 'Price: Low to High'}</option>
              <option value="price-high">{language === 'bn' ? 'মূল্য: বেশি থেকে কম' : 'Price: High to Low'}</option>
              <option value="rating">{language === 'bn' ? 'রেটিং' : 'Customer Rating'}</option>
            </select>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          {/* Desktop Sidebar Filters */}
          <aside className="hidden lg:block space-y-6 bg-white p-6 rounded-2xl border border-[#F0D8D9] h-fit sticky top-24">
            <div className="flex items-center justify-between pb-3 border-b border-[#F0D8D9]">
              <span className="font-serif font-semibold text-base text-elora-charcoal">
                {language === 'bn' ? 'ফিল্টারসমূহ' : 'Refine By'}
              </span>
              {(selectedCategory !== 'all' || selectedBrand !== 'all' || selectedSkinType !== 'all') && (
                <button
                  onClick={() => {
                    setSelectedCategory('all');
                    setSelectedBrand('all');
                    setSelectedSkinType('all');
                    setPriceMax(20000);
                  }}
                  className="text-xs text-[#B83B5E] hover:underline"
                >
                  {language === 'bn' ? 'রিসেট' : 'Clear All'}
                </button>
              )}
            </div>

            {/* Category Filter */}
            <div className="space-y-2">
              <h4 className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">
                {language === 'bn' ? 'ক্যাটাগরি' : 'Category'}
              </h4>
              <div className="space-y-1">
                <button
                  onClick={() => setSelectedCategory('all')}
                  className={`w-full text-left px-2 py-1.5 rounded-lg text-xs font-medium transition-colors ${
                    selectedCategory === 'all' ? 'bg-[#FDF2F3] text-[#B83B5E] font-bold' : 'text-elora-charcoal hover:bg-[#FFF9F2]'
                  }`}
                >
                  {language === 'bn' ? 'সকল ক্যাটাগরি' : 'All Categories'}
                </button>
                {categories.map((c) => (
                  <button
                    key={c.id}
                    onClick={() => setSelectedCategory(c.slug)}
                    className={`w-full text-left px-2 py-1.5 rounded-lg text-xs font-medium transition-colors ${
                      selectedCategory === c.slug ? 'bg-[#FDF2F3] text-[#B83B5E] font-bold' : 'text-elora-charcoal hover:bg-[#FFF9F2]'
                    }`}
                  >
                    {language === 'bn' ? c.name_bn : c.name_en}
                  </button>
                ))}
              </div>
            </div>

            {/* Brand Filter */}
            <div className="space-y-2 pt-2 border-t border-[#F0D8D9]">
              <h4 className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">
                {language === 'bn' ? 'ব্র্যান্ড' : 'Brand'}
              </h4>
              <div className="space-y-1 max-h-40 overflow-y-auto pr-1">
                <button
                  onClick={() => setSelectedBrand('all')}
                  className={`w-full text-left px-2 py-1 rounded text-xs font-medium ${
                    selectedBrand === 'all' ? 'text-[#B83B5E] font-bold' : 'text-elora-charcoal'
                  }`}
                >
                  All Brands
                </button>
                {brands.map((b) => (
                  <button
                    key={b}
                    onClick={() => setSelectedBrand(b)}
                    className={`w-full text-left px-2 py-1 rounded text-xs font-medium ${
                      selectedBrand === b ? 'text-[#B83B5E] font-bold' : 'text-elora-charcoal'
                    }`}
                  >
                    {b}
                  </button>
                ))}
              </div>
            </div>

            {/* Skin Type Filter */}
            <div className="space-y-2 pt-2 border-t border-[#F0D8D9]">
              <h4 className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">
                {language === 'bn' ? 'ত্বকের ধরন' : 'Skin Type'}
              </h4>
              <div className="flex flex-wrap gap-1.5">
                {['All', 'Oily', 'Dry', 'Combination', 'Sensitive'].map((st) => (
                  <button
                    key={st}
                    onClick={() => setSelectedSkinType(st === 'All' ? 'all' : st)}
                    className={`px-2.5 py-1 rounded-full text-[11px] font-medium border transition-colors ${
                      (selectedSkinType === st || (st === 'All' && selectedSkinType === 'all'))
                        ? 'border-[#B83B5E] bg-[#B83B5E] text-white'
                        : 'border-[#EEDAD9] bg-white text-elora-charcoal hover:bg-[#FDF2F3]'
                    }`}
                  >
                    {st}
                  </button>
                ))}
              </div>
            </div>

            {/* Price Range */}
            <div className="space-y-2 pt-2 border-t border-[#F0D8D9]">
              <div className="flex items-center justify-between text-xs">
                <h4 className="font-bold text-[#7A7072] uppercase tracking-wider">
                  {language === 'bn' ? 'সর্বোচ্চ মূল্য' : 'Max Price'}
                </h4>
                <span className="font-bold text-[#B83B5E]">৳{priceMax.toLocaleString()}</span>
              </div>
              <input
                type="range"
                min="1000"
                max="20000"
                step="500"
                value={priceMax}
                onChange={(e) => setPriceMax(Number(e.target.value))}
                className="w-full accent-[#B83B5E]"
              />
            </div>
          </aside>

          {/* Product Grid */}
          <div className="lg:col-span-3">
            {filteredProducts.length === 0 ? (
              <div className="text-center py-20 bg-white rounded-2xl border border-[#F0D8D9] p-8 space-y-3">
                <p className="font-serif text-lg text-elora-charcoal">
                  {language === 'bn' ? 'কোনো পণ্য পাওয়া যায়নি।' : 'No luxury products match your criteria.'}
                </p>
                <button
                  onClick={() => {
                    setSelectedCategory('all');
                    setSelectedBrand('all');
                    setSelectedSkinType('all');
                    setPriceMax(20000);
                  }}
                  className="px-5 py-2 rounded-full bg-[#B83B5E] text-white text-xs font-semibold"
                >
                  {language === 'bn' ? 'ফিল্টার পরিষ্কার করুন' : 'Reset Filters'}
                </button>
              </div>
            ) : (
              <div className="grid grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                {filteredProducts.map((p) => (
                  <ProductCard key={p.id} product={p} />
                ))}
              </div>
            )}
          </div>
        </div>
      </main>

      <SlideCart />
      <Footer />
    </div>
  );
}

export default function ShopPage() {
  return (
    <Suspense fallback={<div className="min-h-screen bg-[#FFF9F2] p-10 text-center font-serif">Loading Maison Elora Vault...</div>}>
      <ShopContent />
    </Suspense>
  );
}
