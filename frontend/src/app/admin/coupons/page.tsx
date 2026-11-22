'use client';

import React, { useState } from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { Coupon } from '@/types';
import { Tag, Plus, Trash2, CheckCircle2 } from 'lucide-react';

export default function AdminCouponsPage() {
  const coupons = useEloraStore((state) => state.coupons);

  const [isModalOpen, setIsModalOpen] = useState(false);
  const [code, setCode] = useState('');
  const [discountType, setDiscountType] = useState<'percentage' | 'fixed_amount' | 'free_shipping'>('percentage');
  const [discountValue, setDiscountValue] = useState(10);
  const [minOrder, setMinOrder] = useState(2000);

  const handleCreateCoupon = (e: React.FormEvent) => {
    e.preventDefault();
    if (!code.trim()) return;

    useEloraStore.setState((state) => ({
      coupons: [
        {
          id: `c-${Date.now()}`,
          code: code.trim().toUpperCase(),
          discount_type: discountType,
          discount_value: Number(discountValue),
          min_order_amount: Number(minOrder),
          is_active: true,
        },
        ...state.coupons,
      ],
    }));

    setCode('');
    setIsModalOpen(false);
  };

  const toggleCouponStatus = (couponId: string) => {
    useEloraStore.setState((state) => ({
      coupons: state.coupons.map((c) =>
        c.id === couponId ? { ...c, is_active: !c.is_active } : c
      ),
    }));
  };

  return (
    <div className="space-y-6 max-w-7xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">Promotions</span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Coupons & Promotional Campaigns
          </h1>
          <p className="text-xs text-[#554B4E]">Create coupon codes with percentage or fixed BDT discount thresholds.</p>
        </div>

        <button
          onClick={() => setIsModalOpen(true)}
          className="px-5 py-2.5 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition-all"
        >
          <Plus className="w-4 h-4" />
          <span>Create Coupon</span>
        </button>
      </div>

      {/* Coupons Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {coupons.map((coupon) => (
          <div
            key={coupon.id}
            className={`p-6 rounded-3xl border transition-all ${
              coupon.is_active ? 'bg-white border-[#F0D8D9] shadow-xs' : 'bg-gray-50 border-gray-200 opacity-60'
            }`}
          >
            <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-3 mb-4">
              <span className="font-mono text-lg font-bold text-elora-charcoal bg-[#FFF9F2] px-3 py-1 rounded-xl border border-[#EEDAD9]">
                {coupon.code}
              </span>
              <button
                onClick={() => toggleCouponStatus(coupon.id)}
                className={`text-[10px] font-bold px-2.5 py-1 rounded-full uppercase ${
                  coupon.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-200 text-gray-700'
                }`}
              >
                {coupon.is_active ? 'Active' : 'Disabled'}
              </button>
            </div>

            <div className="space-y-2 text-xs text-[#554B4E]">
              <div className="flex justify-between">
                <span>Discount Value:</span>
                <span className="font-bold text-[#B83B5E]">
                  {coupon.discount_type === 'percentage'
                    ? `${coupon.discount_value}% OFF`
                    : coupon.discount_type === 'fixed_amount'
                    ? `৳${coupon.discount_value} OFF`
                    : 'Free Delivery'}
                </span>
              </div>
              <div className="flex justify-between">
                <span>Min Order Spend:</span>
                <span className="font-semibold text-elora-charcoal">
                  ৳{(coupon.min_order_amount || 0).toLocaleString()}
                </span>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
          <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-2xl max-w-md w-full p-6 sm:p-8 space-y-4">
            <h3 className="font-serif text-lg font-bold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
              Create Promotional Coupon
            </h3>

            <form onSubmit={handleCreateCoupon} className="space-y-4 text-xs">
              <div>
                <label className="font-semibold block mb-1">Coupon Code *</label>
                <input
                  type="text"
                  required
                  value={code}
                  onChange={(e) => setCode(e.target.value.toUpperCase())}
                  placeholder="e.g. VIP2026"
                  className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl font-mono uppercase outline-none"
                />
              </div>

              <div>
                <label className="font-semibold block mb-1">Discount Type</label>
                <select
                  value={discountType}
                  onChange={(e) => setDiscountType(e.target.value as any)}
                  className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                >
                  <option value="percentage">Percentage Discount (%)</option>
                  <option value="fixed_amount">Fixed Amount (৳ BDT)</option>
                  <option value="free_shipping">Free Shipping</option>
                </select>
              </div>

              {discountType !== 'free_shipping' && (
                <div>
                  <label className="font-semibold block mb-1">Discount Value *</label>
                  <input
                    type="number"
                    required
                    value={discountValue}
                    onChange={(e) => setDiscountValue(Number(e.target.value))}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
              )}

              <div>
                <label className="font-semibold block mb-1">Minimum Order Amount (৳)</label>
                <input
                  type="number"
                  value={minOrder}
                  onChange={(e) => setMinOrder(Number(e.target.value))}
                  className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                />
              </div>

              <div className="flex justify-end gap-2 pt-4 border-t border-[#F0D8D9]">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-5 py-2 rounded-full border border-[#EEDAD9] text-xs font-semibold"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-6 py-2 rounded-full bg-[#B83B5E] text-white text-xs font-semibold shadow-sm"
                >
                  Save Coupon
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
