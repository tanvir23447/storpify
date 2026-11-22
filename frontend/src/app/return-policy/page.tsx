'use client';

import React from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { RefreshCw, CheckCircle2, AlertTriangle, ShieldCheck } from 'lucide-react';

export default function ReturnPolicyPage() {
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
              {language === 'bn' ? 'রিটার্ন ও এক্সচেঞ্জ' : 'Customer Protection'}
            </span>
            <h1 className="font-serif text-3xl sm:text-4xl font-bold text-elora-charcoal">
              {language === 'bn' ? 'রিটার্ন এবং রিফান্ড পলিসি' : 'Return, Exchange & Refund Policy'}
            </h1>
          </div>

          <div className="space-y-6 text-xs sm:text-sm text-[#4A4043] leading-relaxed">
            <div className="p-4 rounded-2xl bg-[#FDF2F3] border border-[#F0D8D9] space-y-2">
              <span className="font-bold text-[#B83B5E] text-base">7-Day Easy Return & Exchange Guarantee</span>
              <p>
                At Maison Elora, customer satisfaction and genuine luxury standards are our highest priorities. If you receive an item that is damaged, defective, or incorrect, you can request an exchange or full refund within 7 days of receiving the package.
              </p>
            </div>

            <div className="space-y-3">
              <h3 className="font-serif text-lg font-bold text-elora-charcoal">Return Eligibility Conditions</h3>
              <ul className="space-y-2 list-disc pl-5">
                <li>Item must remain unopened with original box and tamper-evident seals intact.</li>
                <li>Due to hygiene and cosmetic health standards, opened or tested cosmetics, perfumes, and skincare cannot be returned unless proven defective upon arrival.</li>
                <li>Chocolates must not have been exposed to high heat after delivery.</li>
                <li>Proof of purchase (Order number or invoice) is required.</li>
              </ul>
            </div>

            <div className="space-y-3">
              <h3 className="font-serif text-lg font-bold text-elora-charcoal">Refund Process</h3>
              <p>
                Once returned items are inspected at our Gulshan fulfillment center, refunds are disbursed within 24 to 48 hours directly to your original bKash, Nagad, Rocket, or bank account.
              </p>
            </div>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}
