'use client';

import React from 'react';
import Link from 'next/link';
import { useEloraStore } from '@/store/eloraStore';
import { ProductCard } from '@/components/products/ProductCard';
import { ArrowRight, Sparkles } from 'lucide-react';

export const NewArrivals: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const products = useEloraStore((state) => state.products);

  // Take the 4 primary new arrivals matching Image 02
  const newArrivals = products.slice(0, 4);

  return (
    <section className="py-7 sm:py-9 bg-white border-b border-[#F0DFDF]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Section Heading: Left Title with Gold Flourish, Right "View All ->" (IMAGE 02 Exact Style) */}
        <div className="flex items-center justify-between gap-4 mb-4 sm:mb-5">
          <div className="flex items-center gap-2">
            <span className="text-[#C9A45C] text-lg font-serif">❦</span>
            <h2 className="font-serif text-xl sm:text-2xl text-maison-charcoal font-normal tracking-wide">
              {language === 'bn' ? 'নতুন পণ্য' : 'New Arrivals'}
            </h2>
          </div>

          <Link
            href="/shop?filter=new"
            className="inline-flex items-center gap-1 text-[12px] font-normal text-maison-charcoal hover:text-[#C45E70] transition-colors group cursor-pointer"
          >
            <span>{language === 'bn' ? 'সবগুলো দেখুন' : 'View All'}</span>
            <ArrowRight className="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" />
          </Link>
        </div>

        {/* 4 Compact Product Cards in One Row on Desktop (IMAGE 02 Exact Style) */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-4.5">
          {newArrivals.map((product) => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      </div>
    </section>
  );
};
