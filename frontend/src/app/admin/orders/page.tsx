'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { useEloraStore } from '@/store/eloraStore';
import { OrderStatus } from '@/types';
import { ShoppingBag, Search, Filter, Truck, CheckCircle2, Clock, XCircle, ArrowRight, Eye } from 'lucide-react';

const ORDER_STATUSES: OrderStatus[] = [
  'pending',
  'confirmed',
  'processing',
  'packed',
  'shipped',
  'out_for_delivery',
  'delivered',
  'cancelled',
];

export default function AdminOrdersPage() {
  const orders = useEloraStore((state) => state.orders);
  const updateOrderStatus = useEloraStore((state) => state.updateOrderStatus);

  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState<string>('all');
  const [selectedOrderId, setSelectedOrderId] = useState<string | null>(null);

  const filtered = orders.filter((o) => {
    if (search) {
      const q = search.toLowerCase();
      if (!o.order_number.toLowerCase().includes(q) && !o.customer_name.toLowerCase().includes(q) && !o.customer_phone.includes(q)) return false;
    }
    if (statusFilter !== 'all' && o.order_status !== statusFilter) return false;
    return true;
  });

  const selectedOrder = orders.find((o) => o.id === selectedOrderId);

  return (
    <div className="space-y-6 max-w-7xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">Fulfillment & Operations</span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Orders & Shipping Dispatch ({orders.length})
          </h1>
          <p className="text-xs text-[#554B4E]">Manage customer shipments, courier assignments, and status transitions.</p>
        </div>
      </div>

      {/* Filter / Search Bar */}
      <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="relative w-full sm:w-80">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by order number, customer, phone..."
            className="w-full bg-[#FFF9F2] border border-[#EEDAD9] rounded-xl px-3.5 py-2 text-xs text-elora-charcoal outline-none focus:border-[#B83B5E]"
          />
          <Search className="w-3.5 h-3.5 text-[#7A7072] absolute right-3 top-3" />
        </div>

        <div className="flex gap-1.5 overflow-x-auto w-full sm:w-auto pb-1 sm:pb-0">
          <button
            onClick={() => setStatusFilter('all')}
            className={`px-3 py-1.5 rounded-xl text-xs font-semibold ${
              statusFilter === 'all' ? 'bg-[#B83B5E] text-white' : 'bg-[#FFF9F2] text-elora-charcoal'
            }`}
          >
            All
          </button>
          {['pending', 'processing', 'shipped', 'delivered'].map((st) => (
            <button
              key={st}
              onClick={() => setStatusFilter(st)}
              className={`px-3 py-1.5 rounded-xl text-xs font-semibold capitalize whitespace-nowrap ${
                statusFilter === st ? 'bg-[#B83B5E] text-white' : 'bg-[#FFF9F2] text-elora-charcoal'
              }`}
            >
              {st}
            </button>
          ))}
        </div>
      </div>

      {/* Orders Table */}
      <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-[#FFF9F2] text-[#7A7072] uppercase font-bold text-[10px] tracking-wider border-b border-[#EEDAD9]">
              <tr>
                <th className="py-3 px-4">Order #</th>
                <th className="py-3 px-4">Customer Details</th>
                <th className="py-3 px-4">City / Zone</th>
                <th className="py-3 px-4">Total Amount</th>
                <th className="py-3 px-4">Payment</th>
                <th className="py-3 px-4">Order Status</th>
                <th className="py-3 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#F0D8D9]">
              {filtered.map((order) => (
                <tr key={order.id} className="hover:bg-[#FFFDFD]">
                  <td className="py-3 px-4 font-mono font-bold text-elora-charcoal">
                    #{order.order_number}
                  </td>
                  <td className="py-3 px-4">
                    <span className="font-semibold text-elora-charcoal block">{order.customer_name}</span>
                    <span className="text-[10px] text-[#7A7072]">{order.customer_phone}</span>
                  </td>
                  <td className="py-3 px-4">
                    <span className="text-elora-charcoal font-medium">{order.shipping_district}</span>
                    <span className="text-[10px] text-[#7A7072] block">{order.shipping_division}</span>
                  </td>
                  <td className="py-3 px-4 font-bold text-[#B83B5E]">
                    ৳{order.total_amount.toLocaleString()}
                  </td>
                  <td className="py-3 px-4">
                    <span className="uppercase text-[10px] font-bold px-2 py-0.5 rounded bg-amber-50 text-amber-800">
                      {order.payment_method} ({order.payment_status})
                    </span>
                  </td>
                  <td className="py-3 px-4">
                    <select
                      value={order.order_status}
                      onChange={(e) => updateOrderStatus(order.id, e.target.value as OrderStatus)}
                      className="bg-[#FFF9F2] border border-[#EEDAD9] rounded-lg px-2 py-1 text-xs font-semibold text-elora-charcoal outline-none focus:border-[#B83B5E]"
                    >
                      {ORDER_STATUSES.map((st) => (
                        <option key={st} value={st}>{st.toUpperCase()}</option>
                      ))}
                    </select>
                  </td>
                  <td className="py-3 px-4 text-right">
                    <button
                      onClick={() => setSelectedOrderId(order.id)}
                      className="px-3 py-1 rounded-lg bg-[#FFF9F2] hover:bg-[#FDF2F3] text-[#B83B5E] font-semibold border border-[#EEDAD9] inline-flex items-center gap-1"
                    >
                      <Eye className="w-3.5 h-3.5" />
                      <span>Details</span>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Order Details Drawer / Modal */}
      {selectedOrder && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
          <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-2xl max-w-2xl w-full p-6 sm:p-8 space-y-5 max-h-[90vh] overflow-y-auto">
            <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-3">
              <div>
                <span className="text-xs text-[#7A7072] block">Order Inspection</span>
                <h3 className="font-mono text-xl font-bold text-elora-charcoal">#{selectedOrder.order_number}</h3>
              </div>
              <button
                onClick={() => setSelectedOrderId(null)}
                className="p-1 rounded-full text-elora-charcoal hover:bg-[#FDF2F3]"
              >
                ✕
              </button>
            </div>

            <div className="grid grid-cols-2 gap-4 text-xs bg-[#FFF9F2] p-4 rounded-2xl border border-[#EEDAD9]">
              <div>
                <span className="text-[#7A7072] block">Customer:</span>
                <span className="font-bold text-elora-charcoal">{selectedOrder.customer_name}</span>
                <p>{selectedOrder.customer_phone}</p>
              </div>
              <div>
                <span className="text-[#7A7072] block">Shipping Address:</span>
                <p>{selectedOrder.shipping_address}, {selectedOrder.shipping_district}</p>
              </div>
            </div>

            {/* Items */}
            <div className="space-y-2 text-xs">
              <span className="font-bold text-elora-charcoal block">Ordered Luxury Items:</span>
              {selectedOrder.items.map((item) => (
                <div key={item.id} className="flex justify-between p-2.5 rounded-xl bg-white border border-[#EEDAD9]">
                  <div>
                    <span className="font-semibold text-elora-charcoal block">{item.product_name}</span>
                    <span className="text-[#7A7072]">SKU: {item.product_sku} × {item.quantity}</span>
                  </div>
                  <span className="font-bold text-[#B83B5E]">৳{(item.unit_price * item.quantity).toLocaleString()}</span>
                </div>
              ))}
            </div>

            {/* Financials */}
            <div className="border-t border-[#F0D8D9] pt-3 text-xs space-y-1">
              <div className="flex justify-between">
                <span>Subtotal:</span>
                <span>৳{selectedOrder.subtotal.toLocaleString()}</span>
              </div>
              {selectedOrder.discount_amount > 0 && (
                <div className="flex justify-between text-[#B83B5E]">
                  <span>Discount:</span>
                  <span>-৳{selectedOrder.discount_amount.toLocaleString()}</span>
                </div>
              )}
              <div className="flex justify-between">
                <span>Shipping Charge:</span>
                <span>৳{selectedOrder.shipping_cost.toLocaleString()}</span>
              </div>
              <div className="flex justify-between font-bold text-sm text-elora-charcoal pt-1 border-t border-[#EEDAD9]">
                <span>Grand Total:</span>
                <span className="text-[#B83B5E]">৳{selectedOrder.total_amount.toLocaleString()}</span>
              </div>
            </div>

            <div className="flex justify-end gap-2 pt-2">
              <Link
                href={`/order-success/${selectedOrder.id}`}
                target="_blank"
                className="px-4 py-2 rounded-full border border-[#EEDAD9] text-xs font-semibold hover:bg-[#FFF9F2]"
              >
                View Printable Invoice
              </Link>
              <button
                onClick={() => setSelectedOrderId(null)}
                className="px-5 py-2 rounded-full bg-[#B83B5E] text-white text-xs font-semibold"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
