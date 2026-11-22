'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { Order } from '@/types';
import { Search, Package, Truck, CheckCircle2, Clock, AlertCircle } from 'lucide-react';

export default function OrderTrackingPage() {
  const language = useEloraStore((state) => state.language);
  const orders = useEloraStore((state) => state.orders);

  const [query, setQuery] = useState('');
  const [foundOrder, setFoundOrder] = useState<Order | null>(null);
  const [searched, setSearched] = useState(false);

  const handleTrack = (e: React.FormEvent) => {
    e.preventDefault();
    if (!query.trim()) return;

    const q = query.trim().toUpperCase();
    const found = orders.find(
      (o) => o.order_number.toUpperCase() === q || o.customer_phone.includes(q)
    );

    setFoundOrder(found || null);
    setSearched(true);
  };

  const steps = [
    { key: 'pending', label: language === 'bn' ? 'অর্ডার গৃহীত' : 'Order Placed' },
    { key: 'processing', label: language === 'bn' ? 'প্রক্রিয়াধীন' : 'Processing' },
    { key: 'packed', label: language === 'bn' ? 'প্যাকেজিং সমাপ্ত' : 'Packed' },
    { key: 'shipped', label: language === 'bn' ? 'কুরিয়ারে হস্তান্তর' : 'Dispatched' },
    { key: 'delivered', label: language === 'bn' ? 'ডেলিভারি সম্পন্ন' : 'Delivered' },
  ];

  const getStepIndex = (status: string) => {
    switch (status) {
      case 'pending': return 0;
      case 'confirmed':
      case 'processing': return 1;
      case 'packed': return 2;
      case 'shipped':
      case 'out_for_delivery': return 3;
      case 'delivered': return 4;
      default: return 0;
    }
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header />
      <NavigationBar />

      <main className="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
        <div className="text-center space-y-3 mb-8">
          <Truck className="w-10 h-10 text-[#C9A45C] mx-auto" />
          <h1 className="font-serif text-3xl sm:text-4xl font-semibold text-elora-charcoal">
            {language === 'bn' ? 'অর্ডার ট্র্যাকিং' : 'Real-time Order Tracking'}
          </h1>
          <p className="text-xs sm:text-sm text-[#7A7072] max-w-md mx-auto">
            {language === 'bn'
              ? 'আপনার অর্ডার নম্বর (যেমন ME-2026-1001) অথবা মোবাইল নম্বর প্রদান করে বর্তমান অবস্থা জানুন।'
              : 'Enter your Order Number (e.g. ME-2026-1001) or phone number to check current dispatch status.'}
          </p>
        </div>

        {/* Search Input Box */}
        <form onSubmit={handleTrack} className="max-w-xl mx-auto mb-10 flex gap-2">
          <input
            type="text"
            required
            value={query}
            onChange={(e) => setQuery(e.target.value)}
            placeholder="Order Number (e.g. ME-2026-1001) or Phone"
            className="flex-1 bg-white border border-[#EEDAD9] focus:border-[#B83B5E] rounded-full px-5 py-3 text-xs sm:text-sm text-elora-charcoal outline-none shadow-sm uppercase font-mono"
          />
          <button
            type="submit"
            className="px-6 py-3 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs sm:text-sm font-semibold transition-colors shadow-sm flex items-center gap-2"
          >
            <Search className="w-4 h-4" />
            <span>Track</span>
          </button>
        </form>

        {/* Results */}
        {searched && (
          <div>
            {!foundOrder ? (
              <div className="bg-white p-8 rounded-2xl border border-[#F0D8D9] text-center space-y-3 max-w-md mx-auto">
                <AlertCircle className="w-8 h-8 text-rose-500 mx-auto" />
                <h3 className="font-serif font-bold text-base text-elora-charcoal">
                  {language === 'bn' ? 'অর্ডারটি পাওয়া যায়নি' : 'No Order Found'}
                </h3>
                <p className="text-xs text-[#7A7072]">
                  {language === 'bn'
                    ? 'দয়া করে সঠিক অর্ডার নম্বর দিয়ে আবার চেষ্টা করুন।'
                    : 'Please double-check your order number or phone number and try again.'}
                </p>
              </div>
            ) : (
              <div className="bg-white p-6 sm:p-10 rounded-3xl border border-[#F0D8D9] shadow-luxury space-y-8">
                {/* Status Snapshot Header */}
                <div className="flex flex-wrap items-center justify-between gap-4 pb-4 border-b border-[#F0D8D9]">
                  <div>
                    <span className="text-xs text-[#7A7072] block">Tracking Order:</span>
                    <h3 className="font-mono font-bold text-xl text-elora-charcoal">#{foundOrder.order_number}</h3>
                  </div>
                  <div className="text-right">
                    <span className="text-xs text-[#7A7072] block">Current Status:</span>
                    <span className="px-3 py-1 rounded-full bg-[#FDF2F3] text-[#B83B5E] font-bold text-xs uppercase border border-[#F0D8D9]">
                      {foundOrder.order_status}
                    </span>
                  </div>
                </div>

                {/* Progress Steps */}
                <div className="grid grid-cols-5 gap-2 text-center">
                  {steps.map((st, idx) => {
                    const isDone = idx <= getStepIndex(foundOrder.order_status);
                    return (
                      <div key={st.key} className="space-y-2">
                        <div className={`w-8 h-8 rounded-full mx-auto flex items-center justify-center text-xs font-bold transition-all ${
                          isDone ? 'bg-[#B83B5E] text-white shadow-sm' : 'bg-[#EEDAD9]/60 text-[#7A7072]'
                        }`}>
                          {idx + 1}
                        </div>
                        <span className={`text-[10px] sm:text-xs block font-medium ${
                          isDone ? 'text-elora-charcoal font-semibold' : 'text-[#7A7072]'
                        }`}>
                          {st.label}
                        </span>
                      </div>
                    );
                  })}
                </div>

                {/* Courier info */}
                <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                  <div>
                    <span className="text-[#7A7072] block">Assigned Courier:</span>
                    <span className="font-bold text-elora-charcoal">{foundOrder.courier_name || 'Standard BD Express'}</span>
                  </div>
                  <div>
                    <span className="text-[#7A7072] block">Tracking Code:</span>
                    <span className="font-mono font-bold text-[#B83B5E]">{foundOrder.tracking_number || 'Pending Dispatch'}</span>
                  </div>
                  <div>
                    <span className="text-[#7A7072] block">Payment:</span>
                    <span className="font-bold text-emerald-700 uppercase">{foundOrder.payment_status}</span>
                  </div>
                </div>
              </div>
            )}
          </div>
        )}
      </main>

      <Footer />
    </div>
  );
}
