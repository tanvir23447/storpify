'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { Logo } from '@/components/brand/Logo';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { Search, User, Heart, ShoppingCart, Menu } from 'lucide-react';

interface HeaderProps {
  onToggleMobileNav?: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onToggleMobileNav }) => {
  const router = useRouter();
  const language = useEloraStore((state) => state.language);
  const cart = useEloraStore((state) => state.cart);
  const wishlist = useEloraStore((state) => state.wishlist);
  const setCartOpen = useEloraStore((state) => state.setCartOpen);
  const searchQuery = useEloraStore((state) => state.searchQuery);
  const setSearchQuery = useEloraStore((state) => state.setSearchQuery);

  const [inputVal, setInputVal] = useState(searchQuery);

  const t = language === 'bn' ? bn : en;

  const totalCartCount = cart.reduce((acc, item) => acc + item.quantity, 0);
  const wishlistCount = wishlist.length;

  const handleSearchSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (inputVal.trim()) {
      setSearchQuery(inputVal.trim());
      router.push(`/shop?search=${encodeURIComponent(inputVal.trim())}`);
    }
  };

  return (
    <header className="bg-[#FFFBF9] border-b border-[#F0DFDF] h-[78px] sm:h-[82px] flex items-center transition-all z-40">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div className="flex items-center justify-between gap-4 lg:gap-8">
          
          {/* Left: Mobile Toggle & Brand Logo Lockup */}
          <div className="flex items-center gap-3">
            <button
              onClick={onToggleMobileNav}
              className="lg:hidden p-1.5 text-maison-charcoal hover:text-maison-rose"
              aria-label="Toggle navigation"
            >
              <Menu className="w-6 h-6" />
            </button>
            <Logo variant="primary" />
          </div>

          {/* Center: Wide Rounded Search Bar (IMAGE 02 Exact Style) */}
          <form
            onSubmit={handleSearchSubmit}
            className="hidden md:flex flex-1 max-w-xl mx-4 relative items-center"
          >
            <div className="relative w-full flex items-center">
              <Search className="w-4 h-4 text-[#A8989A] absolute left-4 pointer-events-none" />
              <input
                type="text"
                value={inputVal}
                onChange={(e) => setInputVal(e.target.value)}
                placeholder={t.nav.search_placeholder}
                className="w-full bg-white border border-[#E8D4D6] focus:border-[#C45E70] rounded-full py-2.5 pl-11 pr-14 text-xs sm:text-[13px] text-maison-charcoal placeholder-[#9E9093] outline-none shadow-2xs"
              />
              <button
                type="submit"
                aria-label="Search"
                className="absolute right-1 w-9 h-8 sm:w-10 sm:h-8.5 rounded-full bg-[#C45E70] hover:bg-[#B84A62] text-white flex items-center justify-center transition-colors shadow-2xs"
              >
                <Search className="w-3.5 h-3.5 text-white" />
              </button>
            </div>
          </form>

          {/* Right: Account, Wishlist, Cart Icons (IMAGE 02 Exact Vertical Layout) */}
          <div className="flex items-center gap-5 sm:gap-7">
            {/* Account */}
            <Link
              href="/account"
              className="flex flex-col items-center justify-center text-maison-charcoal hover:text-[#C45E70] transition-colors group cursor-pointer"
            >
              <User className="w-5 h-5 text-maison-charcoal/85 stroke-[1.5] group-hover:text-[#C45E70] transition-colors" />
              <span className="text-[11px] font-normal text-maison-charcoal/90 mt-0.5">
                {t.nav.account}
              </span>
            </Link>

            {/* Wishlist */}
            <Link
              href="/wishlist"
              className="flex flex-col items-center justify-center text-maison-charcoal hover:text-[#C45E70] transition-colors relative group cursor-pointer"
            >
              <div className="relative">
                <Heart className="w-5 h-5 text-maison-charcoal/85 stroke-[1.5] group-hover:text-[#C45E70] transition-colors" />
                <span className="absolute -top-1.5 -right-2 bg-[#C45E70] text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                  {wishlistCount}
                </span>
              </div>
              <span className="text-[11px] font-normal text-maison-charcoal/90 mt-0.5">
                {t.nav.wishlist}
              </span>
            </Link>

            {/* Shopping Cart */}
            <button
              onClick={() => setCartOpen(true)}
              className="flex flex-col items-center justify-center text-maison-charcoal hover:text-[#C45E70] transition-colors relative group cursor-pointer focus:outline-none"
              aria-label="Open cart drawer"
            >
              <div className="relative">
                <ShoppingCart className="w-5 h-5 text-maison-charcoal/85 stroke-[1.5] group-hover:text-[#C45E70] transition-colors" />
                <span className="absolute -top-1.5 -right-2 bg-[#C45E70] text-white text-[9px] font-bold rounded-full w-4 h-4 flex items-center justify-center">
                  {totalCartCount}
                </span>
              </div>
              <span className="text-[11px] font-normal text-maison-charcoal/90 mt-0.5">
                {t.nav.cart}
              </span>
            </button>
          </div>
        </div>

        {/* Mobile Search Input */}
        <form onSubmit={handleSearchSubmit} className="mt-2.5 md:hidden relative">
          <input
            type="text"
            value={inputVal}
            onChange={(e) => setInputVal(e.target.value)}
            placeholder={t.nav.search_placeholder}
            className="w-full bg-white border border-[#E8D4D6] rounded-full py-2 pl-4 pr-11 text-xs text-maison-charcoal outline-none"
          />
          <button
            type="submit"
            className="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-7 rounded-full bg-[#C45E70] text-white flex items-center justify-center"
          >
            <Search className="w-3.5 h-3.5" />
          </button>
        </form>
      </div>
    </header>
  );
};
