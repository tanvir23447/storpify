'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { ChevronDown, Gift, Menu, X } from 'lucide-react';

interface NavigationBarProps {
  isMobileOpen?: boolean;
  onCloseMobile?: () => void;
}

export const NavigationBar: React.FC<NavigationBarProps> = ({
  isMobileOpen = false,
  onCloseMobile,
}) => {
  const pathname = usePathname();
  const language = useEloraStore((state) => state.language);
  const categories = useEloraStore((state) => state.categories);
  const [catDropdownOpen, setCatDropdownOpen] = useState(false);
  const [shopDropdownOpen, setShopDropdownOpen] = useState(false);

  const t = language === 'bn' ? bn : en;

  const navLinks = [
    { label: t.nav.home, href: '/', active: pathname === '/' },
    { label: t.nav.shop, href: '/shop', hasDropdown: true },
    { label: t.nav.skincare, href: '/category/skincare' },
    { label: t.nav.cosmetics, href: '/category/cosmetics' },
    { label: t.nav.perfumes, href: '/category/perfumes' },
    { label: t.nav.chocolates, href: '/category/chocolates' },
    { label: t.nav.offers, href: '/shop?filter=offers', hasGiftIcon: true },
    { label: t.nav.new_arrivals, href: '/shop?filter=new' },
    { label: t.nav.best_sellers, href: '/shop?filter=best' },
  ];

  return (
    <>
      {/* Desktop Navigation Bar (IMAGE 02 Exact Style: 45px height) */}
      <nav className="hidden lg:block bg-white border-b border-[#F0DFDF] text-[13px] h-[45px]">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
          
          {/* All Categories Button (IMAGE 02 Exact Mauve/Rose Pill Box) */}
          <div className="relative h-full flex items-center">
            <button
              onClick={() => setCatDropdownOpen(!catDropdownOpen)}
              onBlur={() => setTimeout(() => setCatDropdownOpen(false), 200)}
              className="h-[36px] flex items-center gap-2 bg-[#A35467] hover:bg-[#924759] text-white px-4 rounded-md text-[13px] font-medium transition-colors cursor-pointer select-none"
            >
              <Menu className="w-4 h-4 text-white" />
              <span>{t.nav.all_categories}</span>
              <ChevronDown className={`w-3.5 h-3.5 transition-transform duration-200 ${catDropdownOpen ? 'rotate-180' : ''}`} />
            </button>

            {/* Dropdown Menu */}
            {catDropdownOpen && (
              <div className="absolute top-[42px] left-0 w-60 bg-white border border-[#F0DFDF] shadow-lg rounded-b-xl py-2 z-50">
                {categories.map((cat) => (
                  <Link
                    key={cat.id}
                    href={`/category/${cat.slug}`}
                    className="flex items-center justify-between px-4 py-2 text-maison-charcoal hover:bg-[#FDF5F6] hover:text-[#C45E70] text-xs transition-colors"
                  >
                    <span className="font-medium">{language === 'bn' ? cat.name_bn : cat.name_en}</span>
                    <span className="text-[10px] text-[#C9A45C]">{cat.subtitle_en?.split('•')[0]}</span>
                  </Link>
                ))}
                <div className="border-t border-[#F0DFDF] mt-1 pt-1 px-4">
                  <Link href="/shop" className="text-xs font-semibold text-[#C45E70] hover:underline">
                    {language === 'bn' ? 'সকল পণ্য দেখুন →' : 'View All Vault →'}
                  </Link>
                </div>
              </div>
            )}
          </div>

          {/* Centered Navigation Links */}
          <div className="flex-1 flex items-center justify-center gap-6 xl:gap-8 h-full ml-4">
            {navLinks.map((link) => {
              if (link.hasDropdown) {
                return (
                  <div
                    key={link.label}
                    className="relative h-full flex items-center"
                    onMouseEnter={() => setShopDropdownOpen(true)}
                    onMouseLeave={() => setShopDropdownOpen(false)}
                  >
                    <Link
                      href={link.href}
                      className="flex items-center gap-1 text-maison-charcoal hover:text-[#C45E70] font-normal transition-colors"
                    >
                      <span>{link.label}</span>
                      <ChevronDown className="w-3 h-3 opacity-60" />
                    </Link>

                    {shopDropdownOpen && (
                      <div className="absolute top-[40px] left-0 w-48 bg-white border border-[#F0DFDF] shadow-md rounded-b-xl py-1.5 z-50">
                        {categories.map((c) => (
                          <Link
                            key={c.id}
                            href={`/category/${c.slug}`}
                            className="block px-3.5 py-1.5 text-xs text-maison-charcoal hover:bg-[#FDF5F6] hover:text-[#C45E70]"
                          >
                            {language === 'bn' ? c.name_bn : c.name_en}
                          </Link>
                        ))}
                      </div>
                    )}
                  </div>
                );
              }

              return (
                <Link
                  key={link.label}
                  href={link.href}
                  className={`h-full flex items-center gap-1.5 text-maison-charcoal transition-colors relative ${
                    link.active
                      ? 'font-bold text-maison-charcoal border-b-2 border-[#A35467]'
                      : link.hasGiftIcon
                      ? 'text-[#C45E70] font-semibold hover:text-[#A35467]'
                      : 'hover:text-[#C45E70] font-normal'
                  }`}
                >
                  {link.hasGiftIcon && <Gift className="w-3.5 h-3.5 text-[#C45E70]" />}
                  <span>{link.label}</span>
                </Link>
              );
            })}
          </div>

          {/* Invisible spacer on the right to keep links centered */}
          <div className="w-[150px] hidden xl:block" />
        </div>
      </nav>

      {/* Mobile Drawer Navigation */}
      {isMobileOpen && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div className="fixed inset-0 bg-black/50" onClick={onCloseMobile} />
          <div className="fixed inset-y-0 left-0 w-4/5 max-w-sm bg-[#FFFBF9] shadow-2xl flex flex-col z-50 p-5 overflow-y-auto">
            <div className="flex items-center justify-between border-b border-[#F0DFDF] pb-3 mb-4">
              <span className="font-serif font-bold text-lg text-maison-charcoal">Maison Elora</span>
              <button onClick={onCloseMobile} className="p-1 text-maison-charcoal">
                <X className="w-5 h-5" />
              </button>
            </div>

            <div className="space-y-1">
              <span className="text-[11px] font-bold text-[#A8989A] uppercase tracking-wider block mb-2">
                {t.nav.all_categories}
              </span>
              {categories.map((c) => (
                <Link
                  key={c.id}
                  href={`/category/${c.slug}`}
                  onClick={onCloseMobile}
                  className="flex justify-between items-center px-3 py-2 text-xs font-medium text-maison-charcoal rounded-lg hover:bg-[#FDF5F6]"
                >
                  <span>{language === 'bn' ? c.name_bn : c.name_en}</span>
                  <span className="text-[#C9A45C]">→</span>
                </Link>
              ))}

              <div className="border-t border-[#F0DFDF] my-3" />

              {navLinks.map((link) => (
                <Link
                  key={link.label}
                  href={link.href}
                  onClick={onCloseMobile}
                  className="block px-3 py-2 text-xs font-medium text-maison-charcoal rounded-lg hover:bg-[#FDF5F6]"
                >
                  {link.label}
                </Link>
              ))}
            </div>
          </div>
        </div>
      )}
    </>
  );
};
