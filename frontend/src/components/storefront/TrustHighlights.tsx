'use client';

import React from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { Award, Truck, RefreshCw, Headphones, ShieldCheck } from 'lucide-react';

export const TrustHighlights: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const t = language === 'bn' ? bn : en;

  const items = [
    {
      icon: Award,
      line1: '100% Authentic',
      line2: 'Imported Products',
    },
    {
      icon: Truck,
      line1: 'Fast & Reliable',
      line2: 'Delivery',
    },
    {
      icon: RefreshCw,
      line1: 'Easy Return &',
      line2: 'Exchange',
    },
    {
      icon: Headphones,
      line1: 'Dedicated',
      line2: 'Customer Support',
    },
    {
      icon: ShieldCheck,
      line1: 'Secure',
      line2: 'Shopping',
    },
  ];

  return (
    <div className="bg-[#FFFDFD] border-b border-[#F0DFDF] h-auto lg:h-[63px] flex items-center py-3 lg:py-0">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 lg:gap-0 divide-y md:divide-y-0 lg:divide-x divide-[#F0DFDF]">
          {items.map((item, idx) => {
            const Icon = item.icon;
            return (
              <div
                key={idx}
                className="flex items-center justify-start lg:justify-center gap-2.5 px-3 py-1.5 lg:py-0"
              >
                <div className="text-[#C45E70] flex-shrink-0">
                  <Icon className="w-5 h-5 sm:w-6 sm:h-6 stroke-[1.5]" />
                </div>
                <div className="leading-[1.2] select-none">
                  <span className="text-[11.5px] sm:text-[12px] font-bold text-maison-charcoal block">
                    {item.line1}
                  </span>
                  <span className="text-[11px] sm:text-[11.5px] font-normal text-maison-charcoal/90 block">
                    {item.line2}
                  </span>
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};
