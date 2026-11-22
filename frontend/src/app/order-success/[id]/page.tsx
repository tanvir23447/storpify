'use client';

import React from 'react';
import { useParams } from 'next/navigation';
import Link from 'next/link';
import Image from 'next/image';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { CheckCircle2, Printer, ArrowRight, Truck, Phone, PackageCheck } from 'lucide-react';

export default function OrderSuccessPage() {
  const params = useParams();
  const orderId = params?.id as string;
  const language = useEloraStore((state) => state.language);
  const orders = useEloraStore((state) => state.orders);

  const order = orders.find((o) => o.id === orderId) || orders[0];

  const handlePrint = () => {
    if (typeof window !== 'undefined') {
      window.print();
    }
  };

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header />

      <main className="flex-1 max-w-4xl mx-auto px-4 sm:px-6 py-12 w-full">
        {/* Success Card */}
        <div className="bg-white p-6 sm:p-12 rounded-3xl border border-[#F0D8D9] shadow-luxury space-y-8">
          
          {/* Header Banner */}
          <div className="text-center space-y-3 pb-6 border-b border-[#F0D8D9]">
            <div className="w-16 h-16 rounded-full bg-[#FDF2F3] border border-[#F0D8D9] flex items-center justify-center mx-auto text-[#B83B5E] shadow-sm">
              <CheckCircle2 className="w-10 h-10" />
            </div>

            <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-widest block">
              {language === 'bn' ? 'অর্ডার সফলভাবে গৃহীত হয়েছে' : 'Order Placed Successfully'}
            </span>

            <h1 className="font-serif text-2xl sm:text-4xl font-semibold text-elora-charcoal">
              {language === 'bn' ? 'ধন্যবাদ, আপনার অর্ডার নিশ্চিত করা হয়েছে' : 'Thank You for Choosing Maison Elora'}
            </h1>

            <p className="text-xs sm:text-sm text-[#554B4E] max-w-md mx-auto">
              {language === 'bn'
                ? `আপনার অর্ডার নম্বর #${order.order_number}। আমাদের টিম শীঘ্রই ফোন করে অর্ডার কনফার্ম করবে।`
                : `Your order #${order.order_number} is received. Our beauty concierge team will confirm shipment shortly.`}
            </p>
          </div>

          {/* Quick Snapshot */}
          <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] text-xs">
            <div>
              <span className="text-[#7A7072] block">Order Number:</span>
              <span className="font-mono font-bold text-elora-charcoal">{order.order_number}</span>
            </div>
            <div>
              <span className="text-[#7A7072] block">Order Status:</span>
              <span className="font-semibold text-amber-700 uppercase">{order.order_status}</span>
            </div>
            <div>
              <span className="text-[#7A7072] block">Payment Method:</span>
              <span className="font-semibold uppercase text-elora-charcoal">{order.payment_method}</span>
            </div>
            <div>
              <span className="text-[#7A7072] block">Total Amount:</span>
              <span className="font-bold text-[#B83B5E] text-sm">৳{order.total_amount.toLocaleString()}</span>
            </div>
          </div>

          {/* Shipping Details */}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs text-[#554B4E] border-b border-[#F0D8D9] pb-6">
            <div className="space-y-1">
              <h4 className="font-serif font-bold text-elora-charcoal text-sm">Delivery Recipient:</h4>
              <p className="font-semibold text-elora-charcoal">{order.customer_name}</p>
              <p>{order.customer_phone}</p>
              {order.customer_email && <p>{order.customer_email}</p>}
            </div>
            <div className="space-y-1">
              <h4 className="font-serif font-bold text-elora-charcoal text-sm">Shipping Address:</h4>
              <p>{order.shipping_address}</p>
              <p>{order.shipping_upazila ? `${order.shipping_upazila}, ` : ''}{order.shipping_district}, {order.shipping_division}</p>
              {order.delivery_instructions && (
                <p className="italic text-[#7A7072]">Notes: {order.delivery_instructions}</p>
              )}
            </div>
          </div>

          {/* Items Table */}
          <div className="space-y-3">
            <h4 className="font-serif font-bold text-elora-charcoal text-base">Purchased Luxury Items:</h4>
            <div className="space-y-2">
              {order.items.map((item) => (
                <div key={item.id} className="flex items-center justify-between p-3 rounded-xl bg-[#FFF9F2] border border-[#EEDAD9] text-xs">
                  <div className="flex items-center gap-3">
                    {item.image_url && (
                      <div className="relative w-10 h-10 rounded-lg overflow-hidden bg-white flex-shrink-0">
                        <Image src={item.image_url} alt={item.product_name} fill className="object-contain p-1" />
                      </div>
                    )}
                    <div>
                      <h5 className="font-semibold text-elora-charcoal">{item.product_name}</h5>
                      <span className="text-[#7A7072]">SKU: {item.product_sku} × {item.quantity}</span>
                    </div>
                  </div>
                  <span className="font-bold text-elora-charcoal">
                    ৳{(item.unit_price * item.quantity).toLocaleString()}
                  </span>
                </div>
              ))}
            </div>
          </div>

          {/* Actions: Print & Continue */}
          <div className="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-[#F0D8D9]">
            <button
              onClick={handlePrint}
              className="w-full sm:w-auto px-6 py-2.5 rounded-full border border-[#EEDAD9] hover:bg-[#FFF9F2] text-xs font-semibold text-elora-charcoal flex items-center justify-center gap-2 transition-colors"
            >
              <Printer className="w-4 h-4 text-[#C9A45C]" />
              <span>{language === 'bn' ? 'চালান প্রিন্ট করুন' : 'Print Invoice'}</span>
            </button>

            <Link
              href="/shop"
              className="w-full sm:w-auto px-8 py-3 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs sm:text-sm font-semibold flex items-center justify-center gap-2 transition-all shadow-md"
            >
              <span>{language === 'bn' ? 'কেনাকাটা চালিয়ে যান' : 'Continue Shopping'}</span>
              <ArrowRight className="w-4 h-4" />
            </Link>
          </div>
        </div>
      </main>

      <Footer />
    </div>
  );
}
