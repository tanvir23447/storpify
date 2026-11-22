'use client';

import React from 'react';
import Link from 'next/link';
import { useEloraStore } from '@/store/eloraStore';
import {
  DollarSign,
  ShoppingBag,
  Package,
  AlertTriangle,
  Clock,
  CheckCircle2,
  Sparkles,
  ArrowRight,
  TrendingUp,
  CreditCard,
  ShieldAlert
} from 'lucide-react';

export default function AdminOverviewPage() {
  const products = useEloraStore((state) => state.products);
  const orders = useEloraStore((state) => state.orders);
  const inventoryBatches = useEloraStore((state) => state.inventoryBatches);

  // Revenue calculation
  const totalRevenue = orders
    .filter((o) => ['confirmed', 'processing', 'packed', 'shipped', 'delivered'].includes(o.order_status))
    .reduce((acc, o) => acc + o.total_amount, 0);

  // Stats
  const pendingOrders = orders.filter((o) => o.order_status === 'pending');
  const pendingPayments = orders.filter((o) => o.payment_status === 'submitted');

  // FEFO Near Expiry items (< 90 days or status near_expiry)
  const nearExpiryBatches = inventoryBatches.filter((b) => {
    const diffDays = (new Date(b.expiry_date).getTime() - Date.now()) / (1000 * 3600 * 24);
    return diffDays < 90 && b.available_quantity > 0;
  });

  const lowStockBatches = inventoryBatches.filter((b) => b.available_quantity <= 15 && b.available_quantity > 0);

  return (
    <div className="space-y-8 max-w-7xl mx-auto">
      
      {/* Welcome Banner */}
      <div className="bg-gradient-to-r from-[#F6D7D9] via-[#FDF2F3] to-[#FFF9F2] p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div className="space-y-1">
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">
            Enterprise Overview
          </span>
          <h1 className="font-serif text-2xl sm:text-3xl font-bold text-elora-charcoal">
            Welcome, Maison Elora Command Center
          </h1>
          <p className="text-xs sm:text-sm text-[#554B4E]">
            Real-time status for inventory, FEFO batch allocations, payments, and brand assets.
          </p>
        </div>

        <Link
          href="/admin/brand-identity"
          className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs font-semibold shadow-sm transition-all"
        >
          <Sparkles className="w-4 h-4 text-elora-blush" />
          <span>Brand Identity & Logo Suite</span>
        </Link>
      </div>

      {/* KPI Stats Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        {/* Total Revenue */}
        <div className="bg-white p-5 rounded-2xl border border-[#F0D8D9] shadow-xs space-y-3">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">Gross Sales</span>
            <div className="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
              ৳
            </div>
          </div>
          <div>
            <span className="text-2xl font-bold text-elora-charcoal">
              ৳{totalRevenue.toLocaleString()}
            </span>
            <div className="flex items-center gap-1 text-[11px] text-emerald-600 mt-1">
              <TrendingUp className="w-3.5 h-3.5" />
              <span>Real-time BDT Transaction Volume</span>
            </div>
          </div>
        </div>

        {/* Total Orders */}
        <div className="bg-white p-5 rounded-2xl border border-[#F0D8D9] shadow-xs space-y-3">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">Total Orders</span>
            <div className="w-8 h-8 rounded-xl bg-[#FDF2F3] text-[#B83B5E] flex items-center justify-center">
              <ShoppingBag className="w-4 h-4" />
            </div>
          </div>
          <div>
            <span className="text-2xl font-bold text-elora-charcoal">{orders.length}</span>
            <span className="text-[11px] text-[#7A7072] block mt-1">
              {pendingOrders.length} pending fulfillment
            </span>
          </div>
        </div>

        {/* Catalog Products */}
        <div className="bg-white p-5 rounded-2xl border border-[#F0D8D9] shadow-xs space-y-3">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">Active Catalog</span>
            <div className="w-8 h-8 rounded-xl bg-[#FFF9F2] text-[#C9A45C] border border-[#C9A45C]/30 flex items-center justify-center">
              <Package className="w-4 h-4" />
            </div>
          </div>
          <div>
            <span className="text-2xl font-bold text-elora-charcoal">{products.length} Products</span>
            <span className="text-[11px] text-[#7A7072] block mt-1">
              Across 4 Luxury Categories
            </span>
          </div>
        </div>

        {/* FEFO Alerts */}
        <div className="bg-white p-5 rounded-2xl border border-[#F0D8D9] shadow-xs space-y-3">
          <div className="flex items-center justify-between">
            <span className="text-xs font-bold text-[#7A7072] uppercase tracking-wider">Expiry / Stock</span>
            <div className="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
              <AlertTriangle className="w-4 h-4" />
            </div>
          </div>
          <div>
            <span className="text-2xl font-bold text-amber-800">{nearExpiryBatches.length + lowStockBatches.length} Alerts</span>
            <span className="text-[11px] text-amber-700 block mt-1 font-medium">
              FEFO monitoring active
            </span>
          </div>
        </div>
      </div>

      {/* Action Banners: Pending Payments & Expiry Alerts */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {/* Pending Payments Alert */}
        <div className="bg-white p-6 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-4">
          <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-3">
            <div className="flex items-center gap-2 text-elora-charcoal font-semibold text-sm">
              <CreditCard className="w-4 h-4 text-[#B83B5E]" />
              <span>Manual MFS Payments Awaiting Verification</span>
            </div>
            <Link href="/admin/payments" className="text-xs text-[#B83B5E] font-semibold hover:underline">
              View All ({pendingPayments.length}) →
            </Link>
          </div>

          {pendingPayments.length === 0 ? (
            <p className="text-xs text-[#7A7072] py-4 text-center">
              All submitted bKash/Nagad/Rocket payments have been verified.
            </p>
          ) : (
            <div className="space-y-2">
              {pendingPayments.map((p) => (
                <div key={p.id} className="flex items-center justify-between p-3 rounded-xl bg-[#FFF9F2] border border-[#EEDAD9] text-xs">
                  <div>
                    <span className="font-mono font-bold text-elora-charcoal">#{p.order_number}</span>
                    <span className="text-[#7A7072] ml-2 font-mono">Trx: {p.transaction_id || 'N/A'}</span>
                  </div>
                  <div className="flex items-center gap-3">
                    <span className="font-bold text-[#B83B5E]">৳{p.total_amount.toLocaleString()}</span>
                    <Link
                      href="/admin/payments"
                      className="px-2.5 py-1 rounded bg-[#B83B5E] text-white text-[10px] font-bold"
                    >
                      Verify
                    </Link>
                  </div>
                </div>
              ))}
            </div>
          )}
        </div>

        {/* FEFO Expiry & Stock Watchdog */}
        <div className="bg-white p-6 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-4">
          <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-3">
            <div className="flex items-center gap-2 text-elora-charcoal font-semibold text-sm">
              <ShieldAlert className="w-4 h-4 text-amber-600" />
              <span>FEFO Expiry & Batch Restock Watch</span>
            </div>
            <Link href="/admin/inventory" className="text-xs text-[#B83B5E] font-semibold hover:underline">
              Inventory Module →
            </Link>
          </div>

          <div className="space-y-2">
            {inventoryBatches.slice(0, 3).map((b) => (
              <div key={b.id} className="flex items-center justify-between p-3 rounded-xl bg-[#FFF9F2] border border-[#EEDAD9] text-xs">
                <div>
                  <span className="font-mono font-bold text-elora-charcoal">{b.batch_number}</span>
                  <span className="text-[#7A7072] ml-2">Available: {b.available_quantity} pcs</span>
                </div>
                <div className="flex items-center gap-2">
                  <span className="text-[#7A7072]">Exp:</span>
                  <span className="font-semibold text-elora-charcoal">{b.expiry_date}</span>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Recent Orders Table */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-4">
        <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-4">
          <h3 className="font-serif text-lg font-semibold text-elora-charcoal">
            Recent Customer Orders
          </h3>
          <Link href="/admin/orders" className="text-xs font-semibold text-[#B83B5E] hover:underline">
            Manage All Orders →
          </Link>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-[#FFF9F2] text-[#7A7072] uppercase font-bold text-[10px] tracking-wider border-b border-[#EEDAD9]">
              <tr>
                <th className="py-3 px-4">Order #</th>
                <th className="py-3 px-4">Customer</th>
                <th className="py-3 px-4">Phone / City</th>
                <th className="py-3 px-4">Amount</th>
                <th className="py-3 px-4">Payment</th>
                <th className="py-3 px-4">Status</th>
                <th className="py-3 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#F0D8D9]">
              {orders.map((order) => (
                <tr key={order.id} className="hover:bg-[#FFFDFD] transition-colors">
                  <td className="py-3.5 px-4 font-mono font-bold text-elora-charcoal">
                    #{order.order_number}
                  </td>
                  <td className="py-3.5 px-4 font-medium text-elora-charcoal">
                    {order.customer_name}
                  </td>
                  <td className="py-3.5 px-4 text-[#554B4E]">
                    {order.customer_phone} ({order.shipping_district})
                  </td>
                  <td className="py-3.5 px-4 font-bold text-[#B83B5E]">
                    ৳{order.total_amount.toLocaleString()}
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="uppercase text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-800">
                      {order.payment_method} ({order.payment_status})
                    </span>
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="capitalize text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                      {order.order_status}
                    </span>
                  </td>
                  <td className="py-3.5 px-4 text-right">
                    <Link
                      href={`/admin/orders`}
                      className="text-xs font-semibold text-[#B83B5E] hover:underline"
                    >
                      Inspect →
                    </Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
