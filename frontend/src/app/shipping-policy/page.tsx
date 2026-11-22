'use client';

import React from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { Truck, Clock, ShieldCheck, MapPin } from 'lucide-react';

export default function ShippingPolicyPage() {
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
              {language === 'bn' ? 'ডেলিভারি পলিসি' : 'Delivery & Logistics'}
            </span>
            <h1 className="font-serif text-3xl sm:text-4xl font-bold text-elora-charcoal">
              {language === 'bn' ? 'শিপিং এবং ডেলিভারি পলিসি' : 'Shipping & Delivery Policy'}
            </h1>
          </div>

          <div className="space-y-6 text-xs sm:text-sm text-[#4A4043] leading-relaxed">
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-1">
                <span className="font-bold text-elora-charcoal text-base">Inside Dhaka City</span>
                <p>• Delivery Time: 24 to 48 Hours</p>
                <p>• Shipping Charge: ৳70 BDT</p>
                <p className="text-[#B83B5E] font-semibold">• FREE Delivery on orders over ৳2,000</p>
              </div>
              <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-1">
                <span className="font-bold text-elora-charcoal text-base">Outside Dhaka (All BD)</span>
                <p>• Delivery Time: 48 to 72 Hours</p>
                <p>• Shipping Charge: ৳130 BDT</p>
                <p className="text-[#B83B5E] font-semibold">• Cash on Delivery & MFS available</p>
              </div>
            </div>

            <div className="space-y-3">
              <h3 className="font-serif text-lg font-bold text-elora-charcoal">Delivery Partners</h3>
              <p>
                We dispatch packages through trusted top-tier courier networks including <strong>Pathao Courier</strong>, <strong>Steadfast Courier</strong>, and <strong>RedX</strong> to ensure prompt, damage-free, and temperature-conscious delivery of all skincare, fragrances, and chocolates.
              </p>
            </div>

            <div className="space-y-3">
              <h3 className="font-serif text-lg font-bold text-elora-charcoal">Packaging Safety</h3>
              <p>
                All glass serum bottles, perfumes, and chocolates are packaged with multiple protective bubble wrap layers and sturdy luxury boxes to withstand transit vibrations and temperature variations.
              </p>
            </div>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}
