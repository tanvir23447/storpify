'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { ChevronLeft, ChevronRight, ArrowRight } from 'lucide-react';

export const HeroSlider: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const t = language === 'bn' ? bn : en;

  const [currentSlide, setCurrentSlide] = useState(0);

  const slides = [
    {
      id: 1,
      badge: 'Maison Elora',
      titleItalic: 'Premium',
      titleSerif: 'Beauty & Care',
      subtitle: language === 'bn'
        ? 'আসল ও বিশ্বস্ত স্কিনকেয়ার, কসমেটিকস, পারফিউম এবং বিলাসবহুল চকলেটের অনন্য কালেকশন।'
        : 'Explore a curated collection of authentic skincare, cosmetics, perfumes & chocolates.',
      ctaText: language === 'bn' ? 'এখনই কিনুন' : 'Shop Now',
      ctaUrl: '/shop',
      // Ultra-clean 2048x450 luxury campaign composite with zero text, zero buttons, zero dots
      heroImage: '/images/hero/maison-elora-hero-clean.jpg',
    },
    {
      id: 2,
      badge: 'Fragrance Vault',
      titleItalic: 'Designer',
      titleSerif: 'Perfumes',
      subtitle: language === 'bn'
        ? 'ভারসাচ ও বিখ্যাত ব্র্যান্ডের ১০০% অরিজিনাল বিলাসবহুল সুবাস।'
        : 'Iconic authentic scents from Versace, Chanel, and YSL with verified batch authenticity.',
      ctaText: language === 'bn' ? 'পারফিউম দেখুন' : 'Explore Perfumes',
      ctaUrl: '/category/perfumes',
      heroImage: '/images/hero/maison-elora-hero-clean.jpg',
    }
  ];

  const slide = slides[currentSlide];

  return (
    <section className="relative w-full overflow-hidden bg-[#F8D2D5] border-b border-[#F0DFDF] select-none">
      {/* 
        Hero Container: Desktop Height exactly matches Image 02 (~340px)
        We use the exact Image 02 hero reference banner with live interactive overlay elements!
      */}
      <div className="relative w-full h-[280px] sm:h-[320px] lg:h-[340px] max-w-[1536px] mx-auto">
        
        {/* Full-width composite background image from Image 02 */}
        <div className="absolute inset-0 w-full h-full">
          <Image
            src={slide.heroImage}
            alt="Maison Elora Premium Beauty & Care"
            fill
            className="object-cover object-center"
            priority
          />
        </div>

        {/* Interactive Left Content Overlay matching exact placement of Image 02 */}
        <div className="absolute inset-0 max-w-7xl mx-auto px-6 sm:px-10 lg:px-14 flex items-center pointer-events-none">
          <div className="max-w-[420px] pointer-events-auto space-y-2 sm:space-y-2.5 pt-1">
            
            {/* Small Brand Label */}
            <span className="font-serif text-[13px] sm:text-[14px] text-maison-charcoal tracking-wide block font-normal">
              {slide.badge}
            </span>

            {/* Main Headline (Italic Cursive + Bold Serif) */}
            <h1 className="leading-[1.08] text-maison-charcoal">
              <span className="font-serif italic font-normal text-3xl sm:text-4xl lg:text-[46px] block">
                {slide.titleItalic}
              </span>
              <span className="font-serif font-normal text-3xl sm:text-4xl lg:text-[46px] block tracking-tight">
                {slide.titleSerif}
              </span>
            </h1>

            {/* Subtitle */}
            <p className="text-[11px] sm:text-[12.5px] text-[#554B4E] leading-relaxed max-w-[340px] font-sans pt-0.5">
              {slide.subtitle}
            </p>

            {/* CTA Button (Dusty Rose Pill) */}
            <div className="pt-2">
              <Link
                href={slide.ctaUrl}
                className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#B84A62] hover:bg-[#A33C53] text-white text-[12px] sm:text-[13px] font-medium tracking-wide shadow-xs transition-transform active:scale-95"
              >
                <span>{slide.ctaText}</span>
                <ArrowRight className="w-3.5 h-3.5" />
              </Link>
            </div>
          </div>
        </div>

        {/* Carousel Navigation Arrow Buttons (IMAGE 02 Exact Style: Small Semi-Transparent Circles) */}
        <button
          onClick={() => setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length)}
          className="absolute left-3 sm:left-6 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 hover:bg-white text-maison-charcoal shadow-2xs flex items-center justify-center transition-all focus:outline-none cursor-pointer"
          aria-label="Previous slide"
        >
          <ChevronLeft className="w-4 h-4 text-maison-charcoal/80" />
        </button>

        <button
          onClick={() => setCurrentSlide((prev) => (prev + 1) % slides.length)}
          className="absolute right-3 sm:right-6 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/80 hover:bg-white text-maison-charcoal shadow-2xs flex items-center justify-center transition-all focus:outline-none cursor-pointer"
          aria-label="Next slide"
        >
          <ChevronRight className="w-4 h-4 text-maison-charcoal/80" />
        </button>

        {/* Pagination Indicator Dots (IMAGE 02 Exact Style: First dot elongated pill, rest small circles) */}
        <div className="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex items-center gap-1.5 z-20">
          <div className="w-5 h-2 rounded-full bg-[#C45E70]" />
          <div className="w-2 h-2 rounded-full bg-white/90 border border-black/10" />
          <div className="w-2 h-2 rounded-full bg-white/90 border border-black/10" />
          <div className="w-2 h-2 rounded-full bg-white/90 border border-black/10" />
        </div>
      </div>
    </section>
  );
};
