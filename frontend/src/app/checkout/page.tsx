'use client';

import React, { useState } from 'react';
import { useRouter } from 'next/navigation';
import Image from 'next/image';
import Link from 'next/link';
import { AnnouncementBar } from '@/components/storefront/AnnouncementBar';
import { Header } from '@/components/storefront/Header';
import { Footer } from '@/components/storefront/Footer';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { PaymentMethod } from '@/types';
import { ShieldCheck, Truck, ArrowRight, CheckCircle2, Lock, AlertCircle, ShoppingBag } from 'lucide-react';

const BD_DIVISIONS = [
  'Dhaka',
  'Chittagong',
  'Sylhet',
  'Rajshahi',
  'Khulna',
  'Barishal',
  'Rangpur',
  'Mymensingh',
];

export default function CheckoutPage() {
  const router = useRouter();
  const language = useEloraStore((state) => state.language);
  const cart = useEloraStore((state) => state.cart);
  const appliedCoupon = useEloraStore((state) => state.appliedCoupon);
  const clearCart = useEloraStore((state) => state.clearCart);
  const createOrder = useEloraStore((state) => state.createOrder);

  const t = language === 'bn' ? bn : en;

  // Form State
  const [customerName, setCustomerName] = useState('');
  const [customerPhone, setCustomerPhone] = useState('');
  const [customerEmail, setCustomerEmail] = useState('');
  const [shippingDivision, setShippingDivision] = useState('Dhaka');
  const [shippingDistrict, setShippingDistrict] = useState('Dhaka');
  const [shippingUpazila, setShippingUpazila] = useState('');
  const [shippingAddress, setShippingAddress] = useState('');
  const [deliveryNotes, setDeliveryNotes] = useState('');

  // Payment Selection
  const [paymentMethod, setPaymentMethod] = useState<PaymentMethod>('cod');
  const [transactionId, setTransactionId] = useState('');
  const [senderPhone, setSenderPhone] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMsg, setErrorMsg] = useState('');

  const subtotal = cart.reduce((acc, item) => acc + item.product.price * item.quantity, 0);

  // Shipping Calculation
  const isInsideDhaka = shippingDivision.toLowerCase() === 'dhaka' && shippingDistrict.toLowerCase() === 'dhaka';
  const baseShipping = isInsideDhaka ? 70 : 130;
  const isFreeShipping = subtotal >= 2000 || appliedCoupon?.discount_type === 'free_shipping';
  const shippingCost = isFreeShipping ? 0 : baseShipping;

  // Discount
  let discountAmount = 0;
  if (appliedCoupon) {
    if (appliedCoupon.discount_type === 'percentage') {
      discountAmount = Math.round((subtotal * appliedCoupon.discount_value) / 100);
    } else if (appliedCoupon.discount_type === 'fixed_amount') {
      discountAmount = appliedCoupon.discount_value;
    }
  }

  const grandTotal = Math.max(0, subtotal - discountAmount + shippingCost);

  const handlePlaceOrder = (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg('');

    if (cart.length === 0) {
      setErrorMsg(language === 'bn' ? 'আপনার ব্যাগ খালি।' : 'Your cart is empty.');
      return;
    }

    if (!customerName.trim() || !customerPhone.trim() || !shippingAddress.trim()) {
      setErrorMsg(
        language === 'bn'
          ? 'অনুগ্রহ করে নাম, মোবাইল নম্বর এবং ঠিকানা পূরণ করুন।'
          : 'Please enter your full name, phone number, and street address.'
      );
      return;
    }

    // Bangladesh phone validation
    const cleanPhone = customerPhone.replace(/\D/g, '');
    if (cleanPhone.length < 11) {
      setErrorMsg(
        language === 'bn'
          ? 'অনুগ্রহ করে সঠিক ১১ ডিজিটের বাংলাদেশি মোবাইল নম্বর দিন।'
          : 'Please enter a valid 11-digit Bangladesh phone number.'
      );
      return;
    }

    // Manual MFS validation
    if (['bkash', 'nagad', 'rocket'].includes(paymentMethod) && !transactionId.trim()) {
      setErrorMsg(
        language === 'bn'
          ? 'অনুগ্রহ করে ট্রানজেকশন আইডি (TrxID) প্রদান করুন।'
          : 'Please enter the Transaction ID for verification.'
      );
      return;
    }

    setIsSubmitting(true);

    try {
      const orderItems = cart.map((item) => ({
        id: `item-${Date.now()}-${Math.random().toString(36).substr(2, 4)}`,
        order_id: '',
        product_id: item.product.id,
        product_name: item.product.name_en,
        product_sku: item.product.sku,
        unit_price: item.product.price,
        quantity: item.quantity,
        total_price: item.product.price * item.quantity,
        image_url: item.product.images[0],
      }));

      const newOrder = createOrder({
        customer_name: customerName,
        customer_phone: customerPhone,
        customer_email: customerEmail,
        shipping_division: shippingDivision,
        shipping_district: shippingDistrict,
        shipping_upazila: shippingUpazila,
        shipping_address: shippingAddress,
        delivery_instructions: deliveryNotes,
        subtotal,
        discount_amount: discountAmount,
        coupon_code: appliedCoupon?.code,
        shipping_cost: shippingCost,
        total_amount: grandTotal,
        order_status: 'pending',
        payment_status: paymentMethod === 'cod' ? 'pending' : 'submitted',
        payment_method: paymentMethod,
        transaction_id: transactionId.trim() || undefined,
        sender_phone: senderPhone.trim() || undefined,
        notes: deliveryNotes,
        items: orderItems,
      });

      clearCart();
      router.push(`/order-success/${newOrder.id}`);
    } catch (err: any) {
      setErrorMsg(err?.message || 'An error occurred during order creation.');
      setIsSubmitting(false);
    }
  };

  if (cart.length === 0) {
    return (
      <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
        <AnnouncementBar />
        <Header />
        <main className="flex-1 max-w-lg mx-auto px-4 py-20 text-center space-y-4">
          <ShoppingBag className="w-16 h-16 text-[#B83B5E] mx-auto" />
          <h2 className="font-serif text-2xl font-semibold">
            {language === 'bn' ? 'আপনার শপিং ব্যাগ খালি' : 'Your Shopping Bag is Empty'}
          </h2>
          <p className="text-xs text-[#7A7072]">
            {language === 'bn'
              ? 'চেকআউট করার আগে দয়া করে আপনার ব্যাগে কিছু পণ্য যোগ করুন।'
              : 'Add some luxury beauty items to your cart before proceeding to checkout.'}
          </p>
          <Link
            href="/shop"
            className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-[#B83B5E] text-white text-xs font-semibold"
          >
            {t.cart.continue_shopping}
          </Link>
        </main>
        <Footer />
      </div>
    );
  }

  return (
    <div className="min-h-screen flex flex-col bg-[#FFF9F2] text-elora-charcoal">
      <AnnouncementBar />
      <Header />

      <main className="flex-1 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 w-full">
        <div className="flex items-center gap-2 mb-8">
          <Lock className="w-5 h-5 text-[#C9A45C]" />
          <h1 className="font-serif text-2xl sm:text-3xl font-semibold text-elora-charcoal">
            {t.checkout.title}
          </h1>
        </div>

        {errorMsg && (
          <div className="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm flex items-center gap-2">
            <AlertCircle className="w-5 h-5 flex-shrink-0" />
            <span>{errorMsg}</span>
          </div>
        )}

        <form onSubmit={handlePlaceOrder} className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
          
          {/* Customer & Shipping Details (Col 7) */}
          <div className="lg:col-span-7 space-y-8">
            
            {/* 1. Contact Info */}
            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-sm space-y-4">
              <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                1. {t.checkout.contact_info}
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div className="space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.full_name} *
                  </label>
                  <input
                    type="text"
                    required
                    value={customerName}
                    onChange={(e) => setCustomerName(e.target.value)}
                    placeholder="e.g. Nusrat Jahan"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>

                <div className="space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.phone} *
                  </label>
                  <input
                    type="tel"
                    required
                    value={customerPhone}
                    onChange={(e) => setCustomerPhone(e.target.value)}
                    placeholder="01712345678"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>

                <div className="sm:col-span-2 space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.email}
                  </label>
                  <input
                    type="email"
                    value={customerEmail}
                    onChange={(e) => setCustomerEmail(e.target.value)}
                    placeholder="name@example.com (for order updates)"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>
              </div>
            </div>

            {/* 2. Shipping Address */}
            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-sm space-y-4">
              <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                2. {t.checkout.shipping_address}
              </h3>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div className="space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.division} *
                  </label>
                  <select
                    value={shippingDivision}
                    onChange={(e) => {
                      setShippingDivision(e.target.value);
                      if (e.target.value !== 'Dhaka') {
                        setShippingDistrict(e.target.value);
                      }
                    }}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  >
                    {BD_DIVISIONS.map((div) => (
                      <option key={div} value={div}>{div}</option>
                    ))}
                  </select>
                </div>

                <div className="space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.district} *
                  </label>
                  <input
                    type="text"
                    required
                    value={shippingDistrict}
                    onChange={(e) => setShippingDistrict(e.target.value)}
                    placeholder="e.g. Dhaka, Chittagong, Sylhet"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>

                <div className="sm:col-span-2 space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.upazila}
                  </label>
                  <input
                    type="text"
                    value={shippingUpazila}
                    onChange={(e) => setShippingUpazila(e.target.value)}
                    placeholder="e.g. Gulshan, Banani, Uttara, Dhanmondi"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>

                <div className="sm:col-span-2 space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.address} *
                  </label>
                  <textarea
                    rows={3}
                    required
                    value={shippingAddress}
                    onChange={(e) => setShippingAddress(e.target.value)}
                    placeholder="House / Flat / Road / Landmark"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>

                <div className="sm:col-span-2 space-y-1">
                  <label className="font-semibold text-elora-charcoal block">
                    {t.checkout.notes}
                  </label>
                  <input
                    type="text"
                    value={deliveryNotes}
                    onChange={(e) => setDeliveryNotes(e.target.value)}
                    placeholder="e.g. Deliver afternoon, call before arrival"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3.5 py-2.5 text-xs text-elora-charcoal outline-none"
                  />
                </div>
              </div>
            </div>

            {/* 3. Bangladesh Payment Methods (Section 6) */}
            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-sm space-y-4">
              <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                3. {t.checkout.payment_method}
              </h3>

              <div className="space-y-3">
                {/* Cash on Delivery */}
                <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${
                  paymentMethod === 'cod' ? 'border-[#B83B5E] bg-[#FDF2F3]' : 'border-[#EEDAD9] hover:bg-[#FFF9F2]'
                }`}>
                  <input
                    type="radio"
                    name="payment"
                    value="cod"
                    checked={paymentMethod === 'cod'}
                    onChange={() => setPaymentMethod('cod')}
                    className="mt-1 accent-[#B83B5E]"
                  />
                  <div>
                    <span className="font-semibold text-sm text-elora-charcoal block">
                      {t.checkout.cod}
                    </span>
                    <span className="text-xs text-[#7A7072]">
                      {t.checkout.cod_desc}
                    </span>
                  </div>
                </label>

                {/* bKash Manual */}
                <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${
                  paymentMethod === 'bkash' ? 'border-[#E2136E] bg-[#FDF2F5]' : 'border-[#EEDAD9] hover:bg-[#FFF9F2]'
                }`}>
                  <input
                    type="radio"
                    name="payment"
                    value="bkash"
                    checked={paymentMethod === 'bkash'}
                    onChange={() => setPaymentMethod('bkash')}
                    className="mt-1 accent-[#E2136E]"
                  />
                  <div className="flex-1">
                    <div className="flex items-center justify-between">
                      <span className="font-semibold text-sm text-elora-charcoal">
                        {t.checkout.bkash}
                      </span>
                      <span className="px-2 py-0.5 rounded bg-[#E2136E] text-white text-[10px] font-bold">bKash</span>
                    </div>
                    <span className="text-xs text-[#7A7072] block mt-0.5">
                      Merchant / Send Money Number: <strong className="text-elora-charcoal">01700000000</strong>
                    </span>
                  </div>
                </label>

                {/* Nagad Manual */}
                <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${
                  paymentMethod === 'nagad' ? 'border-[#F7941D] bg-[#FFF9F2]' : 'border-[#EEDAD9] hover:bg-[#FFF9F2]'
                }`}>
                  <input
                    type="radio"
                    name="payment"
                    value="nagad"
                    checked={paymentMethod === 'nagad'}
                    onChange={() => setPaymentMethod('nagad')}
                    className="mt-1 accent-[#F7941D]"
                  />
                  <div className="flex-1">
                    <div className="flex items-center justify-between">
                      <span className="font-semibold text-sm text-elora-charcoal">
                        {t.checkout.nagad}
                      </span>
                      <span className="px-2 py-0.5 rounded bg-[#F7941D] text-white text-[10px] font-bold">Nagad</span>
                    </div>
                    <span className="text-xs text-[#7A7072] block mt-0.5">
                      Official Nagad Number: <strong className="text-elora-charcoal">01800000000</strong>
                    </span>
                  </div>
                </label>

                {/* Rocket Manual */}
                <label className={`flex items-start gap-3 p-4 rounded-2xl border cursor-pointer transition-all ${
                  paymentMethod === 'rocket' ? 'border-[#8C3494] bg-[#FCF5FD]' : 'border-[#EEDAD9] hover:bg-[#FFF9F2]'
                }`}>
                  <input
                    type="radio"
                    name="payment"
                    value="rocket"
                    checked={paymentMethod === 'rocket'}
                    onChange={() => setPaymentMethod('rocket')}
                    className="mt-1 accent-[#8C3494]"
                  />
                  <div className="flex-1">
                    <div className="flex items-center justify-between">
                      <span className="font-semibold text-sm text-elora-charcoal">
                        {t.checkout.rocket}
                      </span>
                      <span className="px-2 py-0.5 rounded bg-[#8C3494] text-white text-[10px] font-bold">Rocket</span>
                    </div>
                    <span className="text-xs text-[#7A7072] block mt-0.5">
                      Official Rocket Number: <strong className="text-elora-charcoal">01900000000</strong>
                    </span>
                  </div>
                </label>
              </div>

              {/* MFS Inputs (TrxID) if bKash/Nagad/Rocket */}
              {['bkash', 'nagad', 'rocket'].includes(paymentMethod) && (
                <div className="p-4 rounded-2xl bg-[#FFF9F2] border border-[#C9A45C]/40 space-y-3 mt-4 text-xs">
                  <p className="text-[#554B4E] leading-relaxed">
                    {t.checkout.mfs_instructions} Total Amount: <strong className="text-[#B83B5E] text-sm">৳{grandTotal.toLocaleString()}</strong>
                  </p>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div className="space-y-1">
                      <label className="font-semibold text-elora-charcoal block">
                        {t.checkout.trx_id} *
                      </label>
                      <input
                        type="text"
                        required
                        value={transactionId}
                        onChange={(e) => setTransactionId(e.target.value.toUpperCase())}
                        placeholder="e.g. 9J8K2LA71"
                        className="w-full bg-white border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3 py-2 text-xs font-mono uppercase text-elora-charcoal outline-none"
                      />
                    </div>
                    <div className="space-y-1">
                      <label className="font-semibold text-elora-charcoal block">
                        {t.checkout.sender_number}
                      </label>
                      <input
                        type="tel"
                        value={senderPhone}
                        onChange={(e) => setSenderPhone(e.target.value)}
                        placeholder="Sender 01XXXXXXXXX"
                        className="w-full bg-white border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3 py-2 text-xs text-elora-charcoal outline-none"
                      />
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>

          {/* Order Summary & Place Order Button (Col 5) */}
          <div className="lg:col-span-5 space-y-6">
            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-sm space-y-6 sticky top-24">
              <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
                {t.checkout.order_summary} ({cart.length})
              </h3>

              {/* Items List */}
              <div className="space-y-3 max-h-64 overflow-y-auto pr-1">
                {cart.map((item) => (
                  <div key={item.product.id} className="flex items-center gap-3 text-xs">
                    <div className="relative w-12 h-12 rounded-lg bg-[#FFF9F2] border border-[#EEDAD9] overflow-hidden flex-shrink-0">
                      <Image
                        src={item.product.images[0] || 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80'}
                        alt={item.product.name_en}
                        fill
                        className="object-contain p-1"
                      />
                    </div>
                    <div className="flex-1 min-w-0">
                      <h4 className="font-medium text-elora-charcoal truncate">
                        {language === 'bn' ? item.product.name_bn : item.product.name_en}
                      </h4>
                      <span className="text-[#7A7072]">Qty: {item.quantity}</span>
                    </div>
                    <span className="font-bold text-elora-charcoal">
                      ৳{(item.product.price * item.quantity).toLocaleString()}
                    </span>
                  </div>
                ))}
              </div>

              {/* Price Calculation */}
              <div className="space-y-2 text-xs text-[#554B4E] border-t border-[#F0D8D9] pt-4">
                <div className="flex justify-between">
                  <span>{t.cart.subtotal}</span>
                  <span className="font-semibold text-elora-charcoal">৳{subtotal.toLocaleString()}</span>
                </div>

                {discountAmount > 0 && (
                  <div className="flex justify-between text-[#B83B5E]">
                    <span>{t.cart.discount} ({appliedCoupon?.code})</span>
                    <span className="font-semibold">-৳{discountAmount.toLocaleString()}</span>
                  </div>
                )}

                <div className="flex justify-between">
                  <div className="flex items-center gap-1">
                    <span>{language === 'bn' ? 'ডেলিভারি চার্জ' : 'Shipping'}</span>
                    <span className="text-[10px] text-[#7A7072]">
                      ({isInsideDhaka ? 'Inside Dhaka' : 'Outside Dhaka'})
                    </span>
                  </div>
                  <span className="font-semibold text-elora-charcoal">
                    {isFreeShipping ? (
                      <span className="text-emerald-600 font-bold">FREE</span>
                    ) : (
                      `৳${shippingCost}`
                    )}
                  </span>
                </div>

                <div className="border-t border-[#F0D8D9] pt-3 flex justify-between text-base font-bold text-elora-charcoal">
                  <span>{t.cart.total}</span>
                  <span className="text-[#B83B5E] text-lg">৳{grandTotal.toLocaleString()}</span>
                </div>
              </div>

              {/* Submit Button */}
              <button
                type="submit"
                disabled={isSubmitting}
                className="w-full py-4 px-6 rounded-full bg-[#B83B5E] hover:bg-[#982845] disabled:opacity-50 text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md active:scale-98"
              >
                {isSubmitting ? (
                  <span>{t.checkout.processing}</span>
                ) : (
                  <>
                    <span>{t.checkout.place_order}</span>
                    <ArrowRight className="w-4 h-4" />
                  </>
                )}
              </button>

              <div className="flex items-center justify-center gap-2 text-[11px] text-[#7A7072]">
                <ShieldCheck className="w-4 h-4 text-[#C9A45C]" />
                <span>Maison Elora Guaranteed Safe & Authentic Checkout</span>
              </div>
            </div>
          </div>
        </form>
      </main>

      <Footer />
    </div>
  );
}
