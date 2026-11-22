'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { en } from '@/locales/en';
import { bn } from '@/locales/bn';
import { X, Plus, Minus, Trash2, ShoppingBag, ArrowRight, Tag, Check, Truck } from 'lucide-react';

export const SlideCart: React.FC = () => {
  const language = useEloraStore((state) => state.language);
  const cart = useEloraStore((state) => state.cart);
  const isCartOpen = useEloraStore((state) => state.isCartOpen);
  const setCartOpen = useEloraStore((state) => state.setCartOpen);
  const updateCartQuantity = useEloraStore((state) => state.updateCartQuantity);
  const removeFromCart = useEloraStore((state) => state.removeFromCart);
  const appliedCoupon = useEloraStore((state) => state.appliedCoupon);
  const applyCoupon = useEloraStore((state) => state.applyCoupon);
  const removeCoupon = useEloraStore((state) => state.removeCoupon);

  const [couponCodeInput, setCouponCodeInput] = useState('');
  const [couponFeedback, setCouponFeedback] = useState<{ success: boolean; message: string } | null>(null);

  const t = language === 'bn' ? bn : en;

  if (!isCartOpen) return null;

  const subtotal = cart.reduce((acc, item) => acc + item.product.price * item.quantity, 0);

  // Free shipping threshold: ৳2,000
  const freeShippingThreshold = 2000;
  const isFreeShipping = subtotal >= freeShippingThreshold || appliedCoupon?.discount_type === 'free_shipping';
  const shippingCharge = isFreeShipping ? 0 : 70;
  const remainingForFreeShipping = Math.max(0, freeShippingThreshold - subtotal);
  const freeShippingProgress = Math.min(100, Math.round((subtotal / freeShippingThreshold) * 100));

  // Calculate discount
  let discountAmount = 0;
  if (appliedCoupon) {
    if (appliedCoupon.discount_type === 'percentage') {
      discountAmount = Math.round((subtotal * appliedCoupon.discount_value) / 100);
    } else if (appliedCoupon.discount_type === 'fixed_amount') {
      discountAmount = appliedCoupon.discount_value;
    }
  }

  const grandTotal = Math.max(0, subtotal - discountAmount + shippingCharge);

  const handleApplyCoupon = (e: React.FormEvent) => {
    e.preventDefault();
    if (!couponCodeInput.trim()) return;
    const res = applyCoupon(couponCodeInput, subtotal);
    setCouponFeedback(res);
  };

  return (
    <div className="fixed inset-0 z-50 overflow-hidden">
      {/* Backdrop */}
      <div
        className="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity duration-300"
        onClick={() => setCartOpen(false)}
      />

      <div className="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div className="w-screen max-w-md bg-[#FFF9F2] shadow-2xl flex flex-col border-l border-[#EEDAD9]">
          
          {/* Cart Header */}
          <div className="p-4 sm:p-5 border-b border-[#EEDAD9] flex items-center justify-between bg-white">
            <div className="flex items-center gap-2">
              <ShoppingBag className="w-5 h-5 text-[#B83B5E]" />
              <h3 className="font-serif text-lg font-semibold text-elora-charcoal">
                {t.cart.title} ({cart.reduce((a, b) => a + b.quantity, 0)})
              </h3>
            </div>
            <button
              onClick={() => setCartOpen(false)}
              className="p-1.5 text-elora-charcoal hover:text-[#B83B5E] rounded-full hover:bg-[#FDF2F3] transition-colors"
              aria-label="Close cart"
            >
              <X className="w-5 h-5" />
            </button>
          </div>

          {/* Free Shipping Progress Meter */}
          <div className="bg-[#FDF2F3] px-4 py-3 border-b border-[#F0D8D9]">
            <div className="flex items-center gap-2 text-xs font-medium text-elora-charcoal">
              <Truck className="w-4 h-4 text-[#B83B5E]" />
              {isFreeShipping ? (
                <span className="text-[#B83B5E] font-semibold">{t.cart.free_shipping_unlocked}</span>
              ) : (
                <span>
                  {t.cart.free_shipping_notice.replace('{amount}', remainingForFreeShipping.toLocaleString())}
                </span>
              )}
            </div>
            <div className="w-full bg-white h-2 rounded-full overflow-hidden mt-2 border border-[#EEDAD9]">
              <div
                className="bg-[#B83B5E] h-full transition-all duration-500 rounded-full"
                style={{ width: `${freeShippingProgress}%` }}
              />
            </div>
          </div>

          {/* Cart Items List */}
          <div className="flex-1 overflow-y-auto p-4 sm:p-5 space-y-4">
            {cart.length === 0 ? (
              <div className="text-center py-16 space-y-4">
                <div className="w-16 h-16 rounded-full bg-[#FDF2F3] border border-[#F0D8D9] flex items-center justify-center mx-auto text-[#B83B5E]">
                  <ShoppingBag className="w-8 h-8" />
                </div>
                <h4 className="font-serif text-lg font-semibold text-elora-charcoal">
                  {t.cart.empty}
                </h4>
                <p className="text-xs sm:text-sm text-[#7A7072] max-w-xs mx-auto">
                  {t.cart.empty_desc}
                </p>
                <button
                  onClick={() => setCartOpen(false)}
                  className="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[#B83B5E] text-white text-xs sm:text-sm font-semibold hover:bg-[#982845] transition-colors"
                >
                  {t.cart.continue_shopping}
                </button>
              </div>
            ) : (
              cart.map((item) => (
                <div
                  key={item.product.id}
                  className="flex gap-3 bg-white p-3 rounded-xl border border-[#F0D8D9] shadow-xs"
                >
                  {/* Thumbnail */}
                  <div className="relative w-20 h-20 rounded-lg overflow-hidden bg-[#FFF9F2] flex-shrink-0 border border-[#EEDAD9]">
                    <Image
                      src={item.product.images[0] || 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80'}
                      alt={item.product.name_en}
                      fill
                      className="object-contain p-1"
                    />
                  </div>

                  {/* Info */}
                  <div className="flex-1 flex flex-col justify-between">
                    <div>
                      <div className="flex items-start justify-between gap-1">
                        <h4 className="font-serif text-xs sm:text-sm font-semibold text-elora-charcoal line-clamp-1">
                          {language === 'bn' ? item.product.name_bn : item.product.name_en}
                        </h4>
                        <button
                          onClick={() => removeFromCart(item.product.id)}
                          className="text-[#7A7072] hover:text-[#B83B5E] p-1"
                          aria-label="Remove item"
                        >
                          <Trash2 className="w-3.5 h-3.5" />
                        </button>
                      </div>
                      <span className="text-[11px] text-[#7A7072]">
                        {item.product.volume_weight || item.product.brand_id}
                      </span>
                    </div>

                    <div className="flex items-center justify-between mt-2">
                      {/* Quantity Controls */}
                      <div className="flex items-center border border-[#EEDAD9] rounded-lg bg-[#FFF9F2]">
                        <button
                          onClick={() => updateCartQuantity(item.product.id, item.quantity - 1)}
                          className="p-1 hover:bg-[#FDF2F3] text-elora-charcoal"
                          aria-label="Decrease quantity"
                        >
                          <Minus className="w-3.5 h-3.5" />
                        </button>
                        <span className="px-2 text-xs font-semibold text-elora-charcoal min-w-[20px] text-center">
                          {item.quantity}
                        </span>
                        <button
                          onClick={() => updateCartQuantity(item.product.id, item.quantity + 1)}
                          className="p-1 hover:bg-[#FDF2F3] text-elora-charcoal"
                          aria-label="Increase quantity"
                        >
                          <Plus className="w-3.5 h-3.5" />
                        </button>
                      </div>

                      <span className="text-xs sm:text-sm font-bold text-[#B83B5E]">
                        ৳{(item.product.price * item.quantity).toLocaleString()}
                      </span>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Cart Footer Summary & Checkout */}
          {cart.length > 0 && (
            <div className="p-4 sm:p-5 bg-white border-t border-[#EEDAD9] space-y-4">
              
              {/* Coupon Form */}
              <form onSubmit={handleApplyCoupon} className="flex gap-2">
                <input
                  type="text"
                  value={couponCodeInput}
                  onChange={(e) => setCouponCodeInput(e.target.value)}
                  placeholder={t.cart.coupon_placeholder}
                  className="flex-1 bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-lg px-3 py-2 text-xs text-elora-charcoal uppercase outline-none"
                />
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#292426] hover:bg-black text-white text-xs font-semibold rounded-lg transition-colors"
                >
                  {t.cart.apply_coupon}
                </button>
              </form>

              {couponFeedback && (
                <p className={`text-xs ${couponFeedback.success ? 'text-emerald-700' : 'text-rose-600'}`}>
                  {couponFeedback.message}
                </p>
              )}

              {appliedCoupon && (
                <div className="flex items-center justify-between text-xs bg-[#FDF2F3] p-2 rounded-lg text-[#B83B5E]">
                  <span className="font-semibold flex items-center gap-1">
                    <Tag className="w-3.5 h-3.5" />
                    {appliedCoupon.code} applied
                  </span>
                  <button onClick={removeCoupon} className="text-xs hover:underline text-rose-700 font-semibold">
                    {t.cart.remove}
                  </button>
                </div>
              )}

              {/* Price Breakdown */}
              <div className="space-y-1.5 text-xs text-[#554B4E]">
                <div className="flex justify-between">
                  <span>{t.cart.subtotal}</span>
                  <span className="font-semibold">৳{subtotal.toLocaleString()}</span>
                </div>

                {discountAmount > 0 && (
                  <div className="flex justify-between text-[#B83B5E]">
                    <span>{t.cart.discount}</span>
                    <span className="font-semibold">-৳{discountAmount.toLocaleString()}</span>
                  </div>
                )}

                <div className="flex justify-between">
                  <span>{t.cart.shipping_estimate}</span>
                  <span className="font-semibold">
                    {isFreeShipping ? (
                      <span className="text-emerald-600 font-bold">{language === 'bn' ? 'ফ্রি' : 'FREE'}</span>
                    ) : (
                      `৳${shippingCharge}`
                    )}
                  </span>
                </div>

                <div className="border-t border-[#EEDAD9] pt-2 flex justify-between text-sm sm:text-base font-bold text-elora-charcoal">
                  <span>{t.cart.total}</span>
                  <span className="text-[#B83B5E]">৳{grandTotal.toLocaleString()}</span>
                </div>
              </div>

              {/* Checkout Action Button */}
              <Link
                href="/checkout"
                onClick={() => setCartOpen(false)}
                className="w-full py-3.5 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white font-semibold text-sm flex items-center justify-center gap-2 transition-all shadow-md active:scale-98"
              >
                <span>{t.cart.checkout}</span>
                <ArrowRight className="w-4 h-4" />
              </Link>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
