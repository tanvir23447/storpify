'use client';

import React, { useState } from 'react';
import { useParams } from 'next/navigation';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { ProductCard } from '@/components/products/ProductCard';
import { SlideCart } from '@/components/cart/SlideCart';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import Link from 'next/link';

export default function CategoryPage() {
  const params = useParams();
  const slug = params?.slug as string;
  const language = useEloraStore((state) => state.language);
  const products = useEloraStore((state) => state.products);
  const categories = useEloraStore((state) => state.categories);
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);

  const t = language === 'bn' ? bn : en;

  const currentCategory = categories.find((c) => c.slug === slug);
  const categoryProducts = currentCategory
    ? products.filter((p) => p.category_id === currentCategory.id)
    : products;

  const title = currentCategory
    ? (language === 'bn' ? currentCategory.name_bn : currentCategory.name_en)
    : (language === 'bn' ? 'ক্যাটাগরি' : 'Category');

  const subtitle = currentCategory
    ? (language === 'bn' ? currentCategory.subtitle_bn : currentCategory.subtitle_en)
    : '';

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header onToggleMobileNav={() => setIsMobileNavOpen(true)} />
      <NavigationBar
        isMobileOpen={isMobileNavOpen}
        onCloseMobile={() => setIsMobileNavOpen(false)}
      />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        {/* Category Header Banner */}
        <div className="bg-gradient-to-r from-[#FDF2F3] via-[#F6D7D9] to-[#FCE8EB] p-8 sm:p-12 rounded-3xl border border-[#F0D8D9] mb-10 text-center space-y-3 shadow-xs">
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-widest">
            {language === 'bn' ? 'আমদানিকৃত সংগ্রহ' : 'Authentic Collection'}
          </span>
          <h1 className="font-serif text-3xl sm:text-5xl font-semibold text-elora-charcoal">
            {title}
          </h1>
          {subtitle && (
            <p className="text-xs sm:text-base text-[#554B4E] max-w-lg mx-auto">
              {subtitle}
            </p>
          )}
        </div>

        {/* Products Grid */}
        <div className="space-y-6">
          <div className="flex items-center justify-between text-xs text-[#7A7072] border-b border-[#EEDAD9] pb-3">
            <span>
              {language === 'bn'
                ? `মোট ${categoryProducts.length}টি পণ্য প্রদর্শিত হচ্ছে`
                : `Showing ${categoryProducts.length} items`}
            </span>
            <Link href="/shop" className="text-[#B83B5E] hover:underline font-semibold">
              {language === 'bn' ? 'সকল কালেকশন দেখুন →' : 'View All Vault →'}
            </Link>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            {categoryProducts.map((p) => (
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
