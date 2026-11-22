'use client';

import React, { useState } from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { PaymentStatus } from '@/types';
import { CreditCard, CheckCircle2, XCircle, Search, AlertCircle, ShieldCheck } from 'lucide-react';

export default function AdminPaymentsPage() {
  const orders = useEloraStore((state) => state.orders);
  const updatePaymentStatus = useEloraStore((state) => state.updatePaymentStatus);

  const [search, setSearch] = useState('');
  const [methodFilter, setMethodFilter] = useState('all');

  const filtered = orders.filter((o) => {
    if (search) {
      const q = search.toLowerCase();
      if (!o.order_number.toLowerCase().includes(q) && !(o.transaction_id || '').toLowerCase().includes(q) && !o.customer_phone.includes(q)) return false;
    }
    if (methodFilter !== 'all' && o.payment_method !== methodFilter) return false;
    return true;
  });

  const handleVerify = (orderId: string) => {
    updatePaymentStatus(orderId, 'verified');
  };

  const handleReject = (orderId: string) => {
    const reason = prompt('Please enter the reason for payment rejection (e.g. TrxID not found in statement):');
    if (reason) {
      updatePaymentStatus(orderId, 'rejected', reason);
    }
  };

  return (
    <div className="space-y-6 max-w-7xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">Financial Clearance</span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Bangladesh MFS Payment Verification
          </h1>
          <p className="text-xs text-[#554B4E]">Verify customer submitted Transaction IDs for bKash, Nagad, and Rocket.</p>
        </div>
      </div>

      {/* Filter / Search Bar */}
      <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="relative w-full sm:w-80">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by order #, phone, TrxID..."
            className="w-full bg-[#FFF9F2] border border-[#EEDAD9] rounded-xl px-3.5 py-2 text-xs text-elora-charcoal outline-none focus:border-[#B83B5E]"
          />
          <Search className="w-3.5 h-3.5 text-[#7A7072] absolute right-3 top-3" />
        </div>

        <div className="flex gap-1.5">
          {['all', 'bkash', 'nagad', 'rocket', 'cod'].map((m) => (
            <button
              key={m}
              onClick={() => setMethodFilter(m)}
              className={`px-3 py-1.5 rounded-xl text-xs font-semibold uppercase ${
                methodFilter === m ? 'bg-[#B83B5E] text-white' : 'bg-[#FFF9F2] text-elora-charcoal'
              }`}
            >
              {m}
            </button>
          ))}
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-[#FFF9F2] text-[#7A7072] uppercase font-bold text-[10px] tracking-wider border-b border-[#EEDAD9]">
              <tr>
                <th className="py-3 px-4">Order #</th>
                <th className="py-3 px-4">Customer</th>
                <th className="py-3 px-4">Method</th>
                <th className="py-3 px-4">Transaction ID (TrxID)</th>
                <th className="py-3 px-4">Amount</th>
                <th className="py-3 px-4">Payment Status</th>
                <th className="py-3 px-4 text-right">Verification Action</th>
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
                  <td className="py-3 px-4 uppercase font-bold text-elora-charcoal">
                    {order.payment_method}
                  </td>
                  <td className="py-3 px-4 font-mono font-bold text-[#B83B5E]">
                    {order.transaction_id || (order.payment_method === 'cod' ? 'COD - Hand to Courier' : 'Pending')}
                  </td>
                  <td className="py-3 px-4 font-bold text-elora-charcoal">
                    ৳{order.total_amount.toLocaleString()}
                  </td>
                  <td className="py-3 px-4">
                    <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                      order.payment_status === 'verified'
                        ? 'bg-emerald-100 text-emerald-800'
                        : order.payment_status === 'submitted'
                        ? 'bg-amber-100 text-amber-800'
                        : order.payment_status === 'rejected'
                        ? 'bg-rose-100 text-rose-800'
                        : 'bg-gray-100 text-gray-800'
                    }`}>
                      {order.payment_status}
                    </span>
                  </td>
                  <td className="py-3 px-4 text-right">
                    {order.payment_status !== 'verified' ? (
                      <div className="flex items-center justify-end gap-1.5">
                        <button
                          onClick={() => handleVerify(order.id)}
                          className="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-[10px] flex items-center gap-1 shadow-xs"
                        >
                          <CheckCircle2 className="w-3 h-3" />
                          <span>Verify</span>
                        </button>
                        <button
                          onClick={() => handleReject(order.id)}
                          className="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-[10px] border border-rose-200"
                        >
                          Reject
                        </button>
                      </div>
                    ) : (
                      <span className="text-emerald-700 text-xs font-semibold flex items-center justify-end gap-1">
                        <CheckCircle2 className="w-3.5 h-3.5" />
                        <span>Verified</span>
                      </span>
                    )}
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
