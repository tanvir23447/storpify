'use client';

import React, { useState } from 'react';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { HeroSlider } from '@/components/storefront/HeroSlider';
import { TrustHighlights } from '@/components/storefront/TrustHighlights';
import { ShopByCategory } from '@/components/storefront/ShopByCategory';
import { NewArrivals } from '@/components/storefront/NewArrivals';
import { SlideCart } from '@/components/cart/SlideCart';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import Link from 'next/link';
import Image from 'next/image';
import { ArrowRight, ShieldCheck, Star } from 'lucide-react';

export default function HomePage() {
  const language = useEloraStore((state) => state.language);
  const products = useEloraStore((state) => state.products);
  const [isMobileNavOpen, setIsMobileNavOpen] = useState(false);

  return (
    <div className="min-h-screen flex flex-col bg-white text-maison-charcoal selection:bg-[#F6D7D9] selection:text-maison-charcoal">
      {/* SECTION A — TOP ANNOUNCEMENT BAR (IMAGE 02 Exact Match) */}
      <AnnouncementBar />

      {/* SECTION B — MAIN BRAND HEADER (IMAGE 02 Exact Match) */}
      <Header onToggleMobileNav={() => setIsMobileNavOpen(true)} />

      {/* SECTION C — MAIN NAVIGATION BAR (IMAGE 02 Exact Match) */}
      <NavigationBar
        isMobileOpen={isMobileNavOpen}
        onCloseMobile={() => setIsMobileNavOpen(false)}
      />

      <main className="flex-1 w-full">
        {/* SECTION D — HERO BANNER (IMAGE 02 Exact Match) */}
        <HeroSlider />

        {/* SECTION E — TRUST / SERVICE BENEFIT STRIP (IMAGE 02 Exact Match) */}
        <TrustHighlights />

        {/* SECTION F — SHOP BY CATEGORY (IMAGE 02 Exact Match) */}
        <ShopByCategory />

        {/* SECTION G — NEW ARRIVALS (IMAGE 02 Exact Match) */}
        <NewArrivals />

        {/* SECTION H — REMAINING HOMEPAGE SECTIONS (Preserving Identity & Aesthetics) */}
        
        {/* Exclusive Curation Spotlight */}
        <section className="py-10 bg-[#FFFBF9] border-b border-[#F0DFDF]">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="bg-white rounded-2xl p-6 sm:p-9 border border-[#F0DFDF] shadow-xs flex flex-col md:flex-row items-center justify-between gap-6">
              <div className="space-y-3 max-w-xl text-center md:text-left">
                <span className="text-[11px] uppercase font-bold tracking-widest text-[#C9A45C]">
                  {language === 'bn' ? 'বিশেষ অফার' : 'Exclusive Curation'}
                </span>
                <h3 className="font-serif text-2xl sm:text-3xl text-maison-charcoal font-normal leading-tight">
                  {language === 'bn' 
                    ? 'আসল আন্তর্জাতিক সৌন্দর্য ও যত্ন' 
                    : 'Authentic Global Luxury Beauty & Care'}
                </h3>
                <p className="text-xs sm:text-sm text-[#554B4E] leading-relaxed">
                  {language === 'bn'
                    ? 'কোরিয়ান গ্লাস স্কিন কেয়ার থেকে শুরু করে ফরাসি সুগন্ধি এবং বেলজিয়ান চকলেট — প্রতিটি পণ্য সরাসরি ব্র্যান্ড অনুমোদিত সোর্স থেকে সংগৃহীত।'
                    : 'From Korean glass skincare to French designer perfumes and Belgian chocolates — every single item is batch-verified for 100% authenticity.'}
                </p>
                <div className="pt-1">
                  <Link
                    href="/shop?filter=featured"
                    className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#B84A62] hover:bg-[#A33C53] text-white text-xs font-medium transition-colors shadow-2xs"
                  >
                    <span>{language === 'bn' ? 'কালেকশন দেখুন' : 'Explore Luxury Vault'}</span>
                    <ArrowRight className="w-3.5 h-3.5" />
                  </Link>
                </div>
              </div>

              {/* Verified Trust Emblem */}
              <div className="flex-shrink-0 bg-[#FDF5F6] p-5 rounded-xl border border-[#C9A45C]/30 text-center space-y-1.5 max-w-xs">
                <ShieldCheck className="w-9 h-9 text-[#C9A45C] mx-auto stroke-[1.5]" />
                <h4 className="font-serif font-bold text-sm text-maison-charcoal">
                  {language === 'bn' ? '১০০% অরিজিনাল গ্যারান্টি' : '100% Authenticity Guaranteed'}
                </h4>
                <p className="text-[11px] text-[#7A7072]">
                  {language === 'bn'
                    ? 'প্রতিটি পণ্যে অফিশিয়াল ব্যাচ নম্বর ও মেয়াদোত্তীর্ণের তারিখ সংরক্ষিত থাকে।'
                    : 'Every batch is tracked with manufacturing origin and FEFO expiry verification.'}
                </p>
              </div>
            </div>
          </div>
        </section>

        {/* Customer Testimonials */}
        <section className="py-10 bg-white border-b border-[#F0DFDF]">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div className="text-center max-w-xl mx-auto mb-8 space-y-1">
              <span className="text-[11px] font-bold text-[#C9A45C] uppercase tracking-widest">
                {language === 'bn' ? 'সম্মানিত গ্রাহকদের মতামত' : 'Customer Experiences'}
              </span>
              <h2 className="font-serif text-xl sm:text-2xl text-maison-charcoal font-normal">
                {language === 'bn' ? 'কেন আমরা পছন্দের শীর্ষে' : 'Loved by Beauty Connoisseurs'}
              </h2>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-5">
              {[
                {
                  quote: 'Received my The Ordinary serum the very next day inside Dhaka. The packaging and verified batch code gave me total peace of mind.',
                  name: 'Nusrat Jahan',
                  city: 'Gulshan, Dhaka',
                },
                {
                  quote: 'The Versace Bright Crystal fragrance is 100% original. Long-lasting scent and elegant packaging. Maison Elora is truly trustworthy.',
                  name: 'Farhana Ahmed',
                  city: 'Banani, Dhaka',
                },
                {
                  quote: 'Ordered the Ferrero Rocher Gold Box for an anniversary gift. Fresh, crisp chocolates delivered with pristine luxury presentation.',
                  name: 'Tanvir Hossain',
                  city: 'Dhanmondi, Dhaka',
                }
              ].map((item, idx) => (
                <div
                  key={idx}
                  className="bg-[#FDF5F6] p-5 rounded-2xl border border-[#F0DFDF] flex flex-col justify-between space-y-3"
                >
                  <div className="space-y-2">
                    <div className="flex gap-1 text-[#C9A45C]">
                      {[...Array(5)].map((_, i) => (
                        <Star key={i} className="w-3.5 h-3.5 fill-current" />
                      ))}
                    </div>
                    <p className="text-xs text-[#554B4E] italic leading-relaxed">
                      "{item.quote}"
                    </p>
                  </div>
                  <div className="border-t border-[#F0DFDF] pt-2.5 flex items-center justify-between text-xs">
                    <div>
                      <h4 className="font-semibold text-maison-charcoal text-[12px]">{item.name}</h4>
                      <span className="text-[#8C7F81] text-[10.5px]">{item.city}</span>
                    </div>
                    <span className="text-[9.5px] text-[#C9A45C] font-semibold bg-white px-2 py-0.5 rounded border border-[#C9A45C]/30">
                      Verified
                    </span>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Newsletter VIP */}
        <section className="py-10 bg-[#FDF5F6]">
          <div className="max-w-2xl mx-auto px-4 text-center space-y-3">
            <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-widest block">
              Maison Elora Privilege
            </span>
            <h3 className="font-serif text-xl sm:text-2xl text-maison-charcoal font-normal">
              {language === 'bn' ? 'মেসন এলোরা ভিআইপি ক্লাবে যুক্ত হোন' : 'Join the Maison Elora VIP Club'}
            </h3>
            <p className="text-xs text-[#554B4E] max-w-sm mx-auto">
              Subscribe to receive first access to limited imports, private sales, and seasonal launches.
            </p>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                alert('Thank you for subscribing to Maison Elora!');
              }}
              className="flex flex-col sm:flex-row gap-2 max-w-md mx-auto pt-2"
            >
              <input
                type="email"
                placeholder="Enter your email address..."
                required
                className="flex-1 px-4 py-2.5 rounded-full bg-white border border-[#E8D4D6] text-xs text-maison-charcoal outline-none focus:border-[#C45E70]"
              />
              <button
                type="submit"
                className="px-5 py-2.5 rounded-full bg-[#B84A62] hover:bg-[#A33C53] text-white text-xs font-semibold transition-colors shadow-2xs"
              >
                Subscribe
              </button>
            </form>
          </div>
        </section>
      </main>

      <SlideCart />
      <Footer />
    </div>
  );
}
