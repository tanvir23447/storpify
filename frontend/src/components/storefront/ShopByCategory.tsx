'use client';

import React from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { ArrowRight } from 'lucide-react';

export const ShopByCategory: React.FC = () => {
  const language = useEloraStore((state) => state.language);

  const categories = [
    {
      id: 'skincare',
      name_en: 'Skincare',
      name_bn: 'স্কিনকেয়ার',
      subtitle: 'Face Care • Serum • Moisturizer',
      image: '/images/categories/category-skincare-clean.jpg',
      href: '/category/skincare',
    },
    {
      id: 'cosmetics',
      name_en: 'Cosmetics',
      name_bn: 'কসমেটিকস',
      subtitle: 'Makeup • Lip Care • Beauty Essentials',
      image: '/images/categories/category-cosmetics-clean.jpg',
      href: '/category/cosmetics',
    },
    {
      id: 'perfumes',
      name_en: 'Perfumes',
      name_bn: 'সুগন্ধি / পারফিউম',
      subtitle: 'Women • Men • Unisex Fragrance',
      image: '/images/categories/category-perfumes-clean.jpg',
      href: '/category/perfumes',
    },
    {
      id: 'chocolates',
      name_en: 'Chocolates',
      name_bn: 'চকলেট',
      subtitle: 'Imported • Gift Box • Premium Treats',
      image: '/images/categories/category-chocolates-clean.jpg',
      href: '/category/chocolates',
    },
  ];

  return (
    <section className="py-7 sm:py-9 bg-[#FFFBF9]">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Centered Heading with delicate gold flourish lines (IMAGE 02 Exact Style) */}
        <div className="flex items-center justify-center gap-3 sm:gap-5 mb-5 sm:mb-6">
          <div className="h-[1px] w-10 sm:w-16 bg-[#C9A45C]/70" />
          <h2 className="font-serif text-xl sm:text-2xl text-maison-charcoal tracking-wide font-normal">
            Shop by Category
          </h2>
          <div className="h-[1px] w-10 sm:w-16 bg-[#C9A45C]/70" />
        </div>

        {/* 4 Wide Rectangular Cards in One Row (IMAGE 02 Exact Proportion) */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
          {categories.map((cat) => (
            <Link
              key={cat.id}
              href={cat.href}
              className="group bg-[#FDF5F6] border border-[#F0DFDF] rounded-2xl overflow-hidden hover:shadow-card transition-all flex flex-col cursor-pointer"
            >
              {/* Category Photo Top Half */}
              <div className="relative w-full h-[115px] sm:h-[125px] bg-white overflow-hidden">
                <Image
                  src={cat.image}
                  alt={cat.name_en}
                  fill
                  className="object-cover object-center group-hover:scale-102 transition-transform duration-300"
                />
              </div>

              {/* Lower Details Area */}
              <div className="p-3 sm:p-3.5 flex items-center justify-between gap-2 bg-[#FDF5F6]">
                <div>
                  <h3 className="font-serif text-[15px] sm:text-[16px] font-bold text-maison-charcoal leading-tight">
                    {language === 'bn' ? cat.name_bn : cat.name_en}
                  </h3>
                  <span className="text-[10.5px] sm:text-[11px] text-[#7A7072] block mt-0.5 line-clamp-1">
                    {cat.subtitle}
                  </span>
                </div>

                {/* Round Rose Arrow Button */}
                <div className="w-6 h-6 sm:w-7 sm:h-7 rounded-full bg-[#C45E70] text-white flex items-center justify-center flex-shrink-0 group-hover:bg-[#B84A62] transition-colors shadow-2xs">
                  <ArrowRight className="w-3.5 h-3.5" />
                </div>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
};
