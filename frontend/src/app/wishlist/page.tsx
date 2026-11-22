'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { ProductCard } from '@/components/products/ProductCard';
import { SlideCart } from '@/components/cart/SlideCart';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { Heart, ShoppingBag } from 'lucide-react';

export default function WishlistPage() {
  const language = useEloraStore((state) => state.language);
  const wishlist = useEloraStore((state) => state.wishlist);
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header onToggleMobileNav={() => setIsMobileNavOpen(true)} />
      <NavigationBar
        isMobileOpen={isMobileNavOpen}
        onCloseMobile={() => setIsMobileNavOpen(false)}
      />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <div className="flex items-center gap-3 mb-8">
          <Heart className="w-6 h-6 text-[#B83B5E] fill-[#B83B5E]" />
          <h1 className="font-serif text-2xl sm:text-4xl font-semibold text-elora-charcoal">
            {language === 'bn' ? 'আমার পছন্দের উইশলিস্ট' : 'My Curated Wishlist'}
          </h1>
          <span className="text-xs font-bold text-[#B83B5E] bg-[#FDF2F3] px-2.5 py-1 rounded-full border border-[#F0D8D9]">
            {wishlist.length}
          </span>
        </div>

        {wishlist.length === 0 ? (
          <div className="text-center py-20 bg-white rounded-3xl border border-[#F0D8D9] p-8 max-w-md mx-auto space-y-4">
            <Heart className="w-12 h-12 text-[#E4C1C4] mx-auto" />
            <h3 className="font-serif text-xl font-semibold text-elora-charcoal">
              {language === 'bn' ? 'আপনার উইশলিস্ট খালি' : 'Your Wishlist is Empty'}
            </h3>
            <p className="text-xs text-[#7A7072]">
              {language === 'bn'
                ? 'পছন্দের পণ্যগুলো উইশলিস্টে যুক্ত করতে পণ্যের উপরে থাকা হার্ট আইকনে ক্লিক করুন।'
                : 'Save your dream luxury beauty items here by clicking the heart icon on any product.'}
            </p>
            <Link
              href="/shop"
              className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#B83B5E] text-white text-xs font-semibold hover:bg-[#982845] transition-colors shadow-sm"
            >
              {language === 'bn' ? 'পণ্য দেখুন' : 'Explore Catalog'}
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            {wishlist.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        )}
      </main>

      <SlideCart />
      <Footer />
    </div>
  );
}
