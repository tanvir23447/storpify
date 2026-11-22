'use client';

import React from 'react';
import Link from 'next/link';
import { Logo } from '@/components/brand/Logo';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { Phone, Mail, MapPin, ShieldCheck, Heart, Sparkles } from 'lucide-react';

export const Footer: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const brandSettings = useEloraStore((state) => state.brandSettings);
  const t = language === 'bn' ? bn : en;

  return (
    <footer className="bg-[#292426] text-[#FFF9F2] pt-14 pb-8 border-t border-[#C9A45C]/30">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Main Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-10 pb-12 border-b border-white/10">
          
          {/* Brand Info */}
          <div className="lg:col-span-2 space-y-4">
            <div className="bg-white/95 p-3 rounded-2xl inline-block shadow-md">
              <Logo variant="footer" href="/" />
            </div>

            <p className="text-xs sm:text-sm text-[#E4D7D9] leading-relaxed max-w-sm font-sans">
              {language === 'bn'
                ? 'মেসন এলোরা — বাংলাদেশে প্রিমিয়াম আমদানিকৃত ১০০% অরিজিনাল স্কিনকেয়ার, কসমেটিকস, পারফিউম ও বিলাসবহুল চকলেটের সবচেয়ে বিশ্বস্ত ঠিকানা।'
                : 'Maison Elora is Bangladesh’s premier destination for authentic, 100% genuine luxury skincare, cosmetics, designer fragrances, and imported gourmet chocolates.'}
            </p>

            <div className="pt-2 space-y-2 text-xs text-[#E4D7D9]">
              <div className="flex items-center gap-2">
                <MapPin className="w-4 h-4 text-[#C9A45C]" />
                <span>{brandSettings.business_address}</span>
              </div>
              <div className="flex items-center gap-2">
                <Phone className="w-4 h-4 text-[#C9A45C]" />
                <span>{brandSettings.contact_phone}</span>
              </div>
              <div className="flex items-center gap-2">
                <Mail className="w-4 h-4 text-[#C9A45C]" />
                <span>{brandSettings.contact_email}</span>
              </div>
            </div>
          </div>

          {/* Categories */}
          <div className="space-y-3">
            <h4 className="font-serif text-base font-semibold text-[#FFF9F2] tracking-wide">
              {t.categories.title}
            </h4>
            <ul className="space-y-2 text-xs sm:text-sm text-[#E4D7D9]">
              <li>
                <Link href="/category/skincare" className="hover:text-[#F6D7D9] transition-colors">
                  {t.nav.skincare}
                </Link>
              </li>
              <li>
                <Link href="/category/cosmetics" className="hover:text-[#F6D7D9] transition-colors">
                  {t.nav.cosmetics}
                </Link>
              </li>
              <li>
                <Link href="/category/perfumes" className="hover:text-[#F6D7D9] transition-colors">
                  {t.nav.perfumes}
                </Link>
              </li>
              <li>
                <Link href="/category/chocolates" className="hover:text-[#F6D7D9] transition-colors">
                  {t.nav.chocolates}
                </Link>
              </li>
              <li>
                <Link href="/shop?filter=offers" className="text-[#C9A45C] hover:underline font-semibold">
                  {t.nav.offers}
                </Link>
              </li>
            </ul>
          </div>

          {/* Customer Care */}
          <div className="space-y-3">
            <h4 className="font-serif text-base font-semibold text-[#FFF9F2] tracking-wide">
              {language === 'bn' ? 'কাস্টমার কেয়ার' : 'Customer Care'}
            </h4>
            <ul className="space-y-2 text-xs sm:text-sm text-[#E4D7D9]">
              <li>
                <Link href="/order-tracking" className="hover:text-[#F6D7D9] transition-colors">
                  {language === 'bn' ? 'অর্ডার ট্র্যাকিং' : 'Track Your Order'}
                </Link>
              </li>
              <li>
                <Link href="/shipping-policy" className="hover:text-[#F6D7D9] transition-colors">
                  {language === 'bn' ? 'ডেলিভারি পলিসি' : 'Delivery & Shipping'}
                </Link>
              </li>
              <li>
                <Link href="/return-policy" className="hover:text-[#F6D7D9] transition-colors">
                  {language === 'bn' ? 'রিটার্ন ও এক্সচেঞ্জ' : 'Return & Exchange'}
                </Link>
              </li>
              <li>
                <Link href="/authenticity" className="hover:text-[#F6D7D9] transition-colors">
                  {language === 'bn' ? '১০০% অরিজিনাল গ্যারান্টি' : 'Authenticity Guarantee'}
                </Link>
              </li>
              <li>
                <Link href="/admin" className="text-[#C9A45C] hover:underline font-medium">
                  {t.admin.dashboard}
                </Link>
              </li>
            </ul>
          </div>

          {/* Payment & Security */}
          <div className="space-y-3">
            <h4 className="font-serif text-base font-semibold text-[#FFF9F2] tracking-wide">
              {language === 'bn' ? 'পেমেন্ট মাধ্যম' : 'Payment Methods'}
            </h4>
            <p className="text-xs text-[#E4D7D9]">
              {language === 'bn'
                ? 'ক্যাশ অন ডেলিভারি এবং বাংলাদেশের বিশ্বস্ত এমএফএস গেটওয়ে।'
                : 'Cash on Delivery and verified Bangladesh mobile payments.'}
            </p>
            
            {/* Payment Method Badges */}
            <div className="flex flex-wrap gap-2 pt-1">
              <span className="px-2.5 py-1 rounded bg-[#E2136E] text-white text-[11px] font-bold">
                bKash
              </span>
              <span className="px-2.5 py-1 rounded bg-[#F7941D] text-white text-[11px] font-bold">
                Nagad
              </span>
              <span className="px-2.5 py-1 rounded bg-[#8C3494] text-white text-[11px] font-bold">
                Rocket
              </span>
              <span className="px-2.5 py-1 rounded bg-[#B83B5E] text-white text-[11px] font-bold">
                COD
              </span>
            </div>

            <div className="flex items-center gap-2 text-xs text-[#C9A45C] pt-2">
              <ShieldCheck className="w-4 h-4" />
              <span>256-Bit SSL Encrypted & Secure</span>
            </div>
          </div>
        </div>

        {/* Bottom Bar */}
        <div className="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-[#E4D7D9]/80">
          <p>© {new Date().getFullYear()} {brandSettings.brand_name}. {brandSettings.tagline}. All Rights Reserved.</p>
          <div className="flex items-center gap-1">
            <span>Crafted with luxury for Bangladesh</span>
            <Sparkles className="w-3.5 h-3.5 text-[#C9A45C]" />
          </div>
        </div>
      </div>
    </footer>
  );
};
