'use client';

import React from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { Truck, Gift, ShieldCheck } from 'lucide-react';

export const AnnouncementBar: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const setLanguage = useEloraStore((state) => state.setLanguage);
  const brandSettings = useEloraStore((state) => state.brandSettings);

  const t = language === 'bn' ? bn : en;

  const freeDeliveryText = language === 'bn' 
    ? (brandSettings?.header_announcement_bn || t.announcement.free_delivery)
    : (brandSettings?.header_announcement_en || t.announcement.free_delivery);

  return (
    <div className="bg-[#A35467] text-white text-[12px] h-[34px] sm:h-[35px] flex items-center px-4 sm:px-6 lg:px-8 z-50">
      <div className="max-w-7xl mx-auto w-full flex items-center justify-between">
        
        {/* Messages with Icons & Dividers matching Image 02 */}
        <div className="flex-1 flex items-center justify-center gap-3 sm:gap-6 text-white/95 font-normal tracking-wide text-center">
          
          <div className="flex items-center gap-1.5 whitespace-nowrap">
            <Truck className="w-3.5 h-3.5 text-white/90" />
            <span>{freeDeliveryText}</span>
          </div>

          <span className="text-white/40 font-light hidden md:inline">|</span>

          <div className="hidden sm:flex items-center gap-1.5 whitespace-nowrap">
            <Gift className="w-3.5 h-3.5 text-white/90" />
            <span>{t.announcement.authentic}</span>
          </div>

          <span className="text-white/40 font-light hidden lg:inline">|</span>

          <div className="hidden lg:flex items-center gap-1.5 whitespace-nowrap">
            <ShieldCheck className="w-3.5 h-3.5 text-white/90" />
            <span>{t.announcement.returns}</span>
          </div>
        </div>

        {/* Language Selector at far right */}
        <div className="flex items-center gap-1.5 text-[11px] font-medium text-white/90 pl-3">
          <button
            onClick={() => setLanguage('en')}
            className={`hover:text-white transition-opacity ${
              language === 'en' ? 'font-bold underline underline-offset-2 text-white' : 'opacity-80'
            }`}
          >
            English
          </button>
          <span className="opacity-50">|</span>
          <button
            onClick={() => setLanguage('bn')}
            className={`hover:text-white transition-opacity ${
              language === 'bn' ? 'font-bold underline underline-offset-2 text-white' : 'opacity-80'
            }`}
          >
            বাংলা
          </button>
        </div>
      </div>
    </div>
  );
};
