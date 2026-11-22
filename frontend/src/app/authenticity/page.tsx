'use client';

import React from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { ShieldCheck, Award, CheckCircle2, Globe, Search } from 'lucide-react';

export default function AuthenticityPage() {
  const language = useEloraStore((state) => state.language);

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header />
      <NavigationBar />

      <main className="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
        <div className="bg-white p-6 sm:p-12 rounded-3xl border border-[#F0D8D9] shadow-sm space-y-8">
          <div className="border-b border-[#F0D8D9] pb-6 space-y-2">
            <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-widest">
              {language === 'bn' ? 'অরিজিনাল পণ্যের প্রতিশ্রুতি' : 'Uncompromising Quality'}
            </span>
            <h1 className="font-serif text-3xl sm:text-4xl font-bold text-elora-charcoal">
              {language === 'bn' ? '১০০% আসল পণ্যের গ্যারান্টি' : '100% Authenticity Guarantee'}
            </h1>
          </div>

          <div className="space-y-6 text-xs sm:text-sm text-[#4A4043] leading-relaxed">
            <div className="p-6 rounded-2xl bg-gradient-to-r from-[#F6D7D9] via-[#FDF2F3] to-[#FFF9F2] border border-[#F0D8D9] space-y-3">
              <div className="flex items-center gap-2 text-[#C9A45C]">
                <ShieldCheck className="w-8 h-8" />
                <span className="font-serif font-bold text-lg text-elora-charcoal">Direct Import Sourcing Channel</span>
              </div>
              <p>
                Counterfeit and fake cosmetics are a hazard to personal health. At Maison Elora, we maintain a zero-tolerance policy for replicas, grey-market dilutions, or unauthorized imitations. Every product in our inventory is directly procured from authorized brand distributors across Canada, the UK, France, Italy, and South Korea.
              </p>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-1">
                <Award className="w-5 h-5 text-[#C9A45C]" />
                <h4 className="font-bold text-elora-charcoal">Batch Code Verification</h4>
                <p className="text-xs text-[#7A7072]">Verify production dates and batch codes on CheckFresh and brand serial registries.</p>
              </div>
              <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-1">
                <Globe className="w-5 h-5 text-[#B83B5E]" />
                <h4 className="font-bold text-elora-charcoal">Temperature Controlled</h4>
                <p className="text-xs text-[#7A7072]">Fragrances and chocolates are air-freighted and stored in climate-monitored facilities.</p>
              </div>
              <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-1">
                <CheckCircle2 className="w-5 h-5 text-emerald-700" />
                <h4 className="font-bold text-elora-charcoal">FEFO Freshness</h4>
                <p className="text-xs text-[#7A7072]">Our First-Expiry First-Out inventory engine guarantees you never receive expired stock.</p>
              </div>
            </div>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}
