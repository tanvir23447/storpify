'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { NavigationBar } from '@/components/storefront/NavigationBar';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { User, Package, Heart, MapPin, Shield, LogOut } from 'lucide-react';

export default function AccountPage() {
  const language = useEloraStore((state) => state.language);
  const orders = useEloraStore((state) => state.orders);
  const wishlist = useEloraStore((state) => state.wishlist);

  const [activeTab, setActiveTab] = useState<'orders' | 'profile' | 'addresses'>('orders');

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header />
      <NavigationBar />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <div className="flex items-center justify-between mb-8 pb-4 border-b border-[#EEDAD9]">
          <div className="flex items-center gap-3">
            <div className="w-12 h-12 rounded-full bg-[#FDF2F3] border border-[#F0D8D9] flex items-center justify-center text-[#B83B5E]">
              <User className="w-6 h-6" />
            </div>
            <div>
              <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
                {language === 'bn' ? 'আমার অ্যাকাউন্ট' : 'My Maison Elora Account'}
              </h1>
              <span className="text-xs text-[#7A7072]">
                {language === 'bn' ? 'স্বাগতম, প্রিভিলেজ মেম্বার' : 'Welcome, VIP Privilege Member'}
              </span>
            </div>
          </div>

          <Link
            href="/admin"
            className="flex items-center gap-1.5 px-4 py-2 rounded-full bg-[#C9A45C]/15 border border-[#C9A45C]/40 text-[#C9A45C] text-xs font-semibold hover:bg-[#C9A45C]/25 transition-colors"
          >
            <Shield className="w-4 h-4" />
            <span>Super Admin Portal</span>
          </Link>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
          {/* Navigation Sidebar */}
          <aside className="lg:col-span-3 space-y-2">
            <button
              onClick={() => setActiveTab('orders')}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-all ${
                activeTab === 'orders'
                  ? 'bg-[#B83B5E] text-white shadow-sm'
                  : 'bg-white text-elora-charcoal hover:bg-[#FDF2F3]'
              }`}
            >
              <Package className="w-4 h-4" />
              <span>{language === 'bn' ? 'আমার অর্ডারসমূহ' : 'My Orders'} ({orders.length})</span>
            </button>

            <Link
              href="/wishlist"
              className="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold bg-white text-elora-charcoal hover:bg-[#FDF2F3] transition-all"
            >
              <Heart className="w-4 h-4 text-[#B83B5E]" />
              <span>{language === 'bn' ? 'উইশলিস্ট' : 'My Wishlist'} ({wishlist.length})</span>
            </Link>

            <button
              onClick={() => setActiveTab('addresses')}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-all ${
                activeTab === 'addresses'
                  ? 'bg-[#B83B5E] text-white shadow-sm'
                  : 'bg-white text-elora-charcoal hover:bg-[#FDF2F3]'
              }`}
            >
              <MapPin className="w-4 h-4" />
              <span>{language === 'bn' ? 'সংরক্ষিত ঠিকানা' : 'Saved Addresses'}</span>
            </button>

            <button
              onClick={() => setActiveTab('profile')}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs sm:text-sm font-semibold transition-all ${
                activeTab === 'profile'
                  ? 'bg-[#B83B5E] text-white shadow-sm'
                  : 'bg-white text-elora-charcoal hover:bg-[#FDF2F3]'
              }`}
            >
              <User className="w-4 h-4" />
              <span>{language === 'bn' ? 'প্রোফাইল সেটিংস' : 'Profile Settings'}</span>
            </button>
          </aside>

          {/* Main Content Area */}
          <div className="lg:col-span-9 bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-sm">
            {activeTab === 'orders' && (
              <div className="space-y-6">
                <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                  {language === 'bn' ? 'অর্ডার হিস্টোরি' : 'Order History'}
                </h3>

                {orders.length === 0 ? (
                  <p className="text-xs text-[#7A7072] py-8 text-center">
                    {language === 'bn' ? 'এখনও কোনো অর্ডার করেননি।' : 'No orders placed yet.'}
                  </p>
                ) : (
                  <div className="space-y-4">
                    {orders.map((o) => (
                      <div
                        key={o.id}
                        className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-3"
                      >
                        <div className="flex flex-wrap items-center justify-between gap-2 border-b border-[#EEDAD9] pb-2 text-xs">
                          <div>
                            <span className="font-mono font-bold text-elora-charcoal">#{o.order_number}</span>
                            <span className="text-[#7A7072] ml-2">
                              {new Date(o.created_at).toLocaleDateString()}
                            </span>
                          </div>
                          <div className="flex items-center gap-2">
                            <span className="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold uppercase">
                              {o.order_status}
                            </span>
                            <span className="font-bold text-[#B83B5E]">৳{o.total_amount.toLocaleString()}</span>
                          </div>
                        </div>

                        <div className="text-xs text-[#554B4E] space-y-1">
                          <p><strong>Deliver to:</strong> {o.customer_name} ({o.customer_phone})</p>
                          <p><strong>Address:</strong> {o.shipping_address}, {o.shipping_district}</p>
                          <p><strong>Payment:</strong> {o.payment_method.toUpperCase()} ({o.payment_status})</p>
                        </div>

                        <div className="flex items-center justify-between pt-1">
                          <span className="text-[11px] text-[#7A7072]">{o.items.length} items purchased</span>
                          <Link
                            href={`/order-success/${o.id}`}
                            className="text-xs text-[#B83B5E] font-semibold hover:underline"
                          >
                            View Invoice & Details →
                          </Link>
                        </div>
                      </div>
                    ))}
                  </div>
                )}
              </div>
            )}

            {activeTab === 'addresses' && (
              <div className="space-y-4 text-xs">
                <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                  Default Shipping Address
                </h3>
                <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] space-y-2">
                  <div className="flex justify-between items-center">
                    <span className="font-bold text-elora-charcoal">Primary Residence</span>
                    <span className="text-[10px] font-bold bg-[#B83B5E] text-white px-2 py-0.5 rounded">Default</span>
                  </div>
                  <p>Tasnim Rahman</p>
                  <p>House 14, Road 28, Gulshan 1, Dhaka - 1212</p>
                  <p>Phone: 01711223344</p>
                </div>
              </div>
            )}

            {activeTab === 'profile' && (
              <div className="space-y-4 text-xs max-w-md">
                <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                  Customer Profile
                </h3>
                <div className="space-y-3">
                  <div>
                    <label className="font-semibold block mb-1">Full Name</label>
                    <input type="text" defaultValue="Tasnim Rahman" className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none" />
                  </div>
                  <div>
                    <label className="font-semibold block mb-1">Phone Number</label>
                    <input type="tel" defaultValue="01711223344" className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none" />
                  </div>
                  <div>
                    <label className="font-semibold block mb-1">Email Address</label>
                    <input type="email" defaultValue="tasnim@example.com" className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none" />
                  </div>
                  <button className="px-6 py-2.5 rounded-full bg-[#B83B5E] text-white font-semibold shadow-xs">
                    Save Changes
                  </button>
                </div>
              </div>
            )}
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}
