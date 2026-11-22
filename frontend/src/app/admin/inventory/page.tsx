'use client';

import React, { useState } from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { InventoryBatch } from '@/types';
import {
  Boxes,
  Plus,
  AlertTriangle,
  Calendar,
  CheckCircle2,
  Clock,
  ArrowUpDown,
  Search,
  Sliders,
  DollarSign
} from 'lucide-react';

export default function AdminInventoryPage() {
  const products = useEloraStore((state) => state.products);
  const inventoryBatches = useEloraStore((state) => state.inventoryBatches);
  const addInventoryBatch = useEloraStore((state) => state.addInventoryBatch);
  const updateBatchQuantity = useEloraStore((state) => state.updateBatchQuantity);

  const [search, setSearch] = useState('');
  const [filterStatus, setFilterStatus] = useState<string>('all');
  const [isModalOpen, setIsModalOpen] = useState(false);

  // New Batch Form State
  const [batchNumber, setBatchNumber] = useState('');
  const [productId, setProductId] = useState(products[0]?.id || '');
  const [supplierId, setSupplierId] = useState('EuroLux Logistics UK');
  const [purchaseDate, setPurchaseDate] = useState('2026-03-01');
  const [mfgDate, setMfgDate] = useState('2026-01-01');
  const [expiryDate, setExpiryDate] = useState('2029-01-01');
  const [initialQty, setInitialQty] = useState(50);
  const [costPrice, setCostPrice] = useState(1100);
  const [notes, setNotes] = useState('Direct air freight shipment');

  const openNewBatchModal = () => {
    setBatchNumber(`BATCH-${Date.now().toString().slice(-6)}`);
    setProductId(products[0]?.id || '');
    setIsModalOpen(true);
  };

  const handleCreateBatch = (e: React.FormEvent) => {
    e.preventDefault();
    addInventoryBatch({
      batch_number: batchNumber,
      product_id: productId,
      supplier_id: supplierId,
      purchase_date: purchaseDate,
      manufacturing_date: mfgDate,
      expiry_date: expiryDate,
      initial_quantity: Number(initialQty),
      available_quantity: Number(initialQty),
      purchase_cost: Number(costPrice),
      status: 'active',
      notes,
    });
    setIsModalOpen(false);
  };

  // FEFO sorting: Earliest expiry date first
  const sortedBatches = [...inventoryBatches].sort(
    (a, b) => new Date(a.expiry_date).getTime() - new Date(b.expiry_date).getTime()
  );

  const filtered = sortedBatches.filter((b) => {
    if (search) {
      const q = search.toLowerCase();
      const matchBatch = b.batch_number.toLowerCase().includes(q);
      const prod = products.find((p) => p.id === b.product_id);
      const matchProd = prod?.name_en.toLowerCase().includes(q);
      if (!matchBatch && !matchProd) return false;
    }
    if (filterStatus !== 'all' && b.status !== filterStatus) return false;
    return true;
  });

  return (
    <div className="space-y-6 max-w-7xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">
            Section 8 Business Engine
          </span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Advanced Inventory & FEFO Expiry Management
          </h1>
          <p className="text-xs text-[#554B4E]">
            Automated First-Expiry, First-Out (FEFO) batch tracking for imported luxury skincare, cosmetics, and chocolates.
          </p>
        </div>

        <button
          onClick={openNewBatchModal}
          className="px-5 py-2.5 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition-all"
        >
          <Plus className="w-4 h-4" />
          <span>Receive New Stock Batch</span>
        </button>
      </div>

      {/* FEFO Summary Badges */}
      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
            <CheckCircle2 className="w-5 h-5" />
          </div>
          <div>
            <span className="text-xs text-[#7A7072] block">Active Batches</span>
            <span className="text-lg font-bold text-elora-charcoal">
              {inventoryBatches.filter((b) => b.status === 'active').length} Batches
            </span>
          </div>
        </div>

        <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
            <Clock className="w-5 h-5" />
          </div>
          <div>
            <span className="text-xs text-[#7A7072] block">FEFO Rule Status</span>
            <span className="text-lg font-bold text-amber-800">
              Strict Earliest-Expiry Allocation
            </span>
          </div>
        </div>

        <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] flex items-center gap-3">
          <div className="w-10 h-10 rounded-xl bg-[#FDF2F3] text-[#B83B5E] flex items-center justify-center font-bold">
            <Boxes className="w-5 h-5" />
          </div>
          <div>
            <span className="text-xs text-[#7A7072] block">Total Stock Units</span>
            <span className="text-lg font-bold text-elora-charcoal">
              {inventoryBatches.reduce((a, b) => a + b.available_quantity, 0)} Units Available
            </span>
          </div>
        </div>
      </div>

      {/* Search & Filter */}
      <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="relative w-full sm:w-80">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by batch number or product..."
            className="w-full bg-[#FFF9F2] border border-[#EEDAD9] rounded-xl px-3.5 py-2 text-xs text-elora-charcoal outline-none focus:border-[#B83B5E]"
          />
          <Search className="w-3.5 h-3.5 text-[#7A7072] absolute right-3 top-3" />
        </div>

        <div className="flex gap-2">
          {['all', 'active', 'near_expiry', 'depleted'].map((st) => (
            <button
              key={st}
              onClick={() => setFilterStatus(st)}
              className={`px-3 py-1.5 rounded-xl text-xs font-semibold capitalize ${
                filterStatus === st ? 'bg-[#B83B5E] text-white' : 'bg-[#FFF9F2] text-elora-charcoal hover:bg-[#FDF2F3]'
              }`}
            >
              {st}
            </button>
          ))}
        </div>
      </div>

      {/* Batches Table */}
      <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-[#FFF9F2] text-[#7A7072] uppercase font-bold text-[10px] tracking-wider border-b border-[#EEDAD9]">
              <tr>
                <th className="py-3 px-4">Batch Number</th>
                <th className="py-3 px-4">Target Product</th>
                <th className="py-3 px-4">Available Qty</th>
                <th className="py-3 px-4">Cost / Unit</th>
                <th className="py-3 px-4">Expiry Date (FEFO)</th>
                <th className="py-3 px-4">Status</th>
                <th className="py-3 px-4 text-right">Quick Stock Adjustment</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#F0D8D9]">
              {filtered.map((b) => {
                const prod = products.find((p) => p.id === b.product_id);
                const isNear = (new Date(b.expiry_date).getTime() - Date.now()) / (1000 * 3600 * 24) < 180;

                return (
                  <tr key={b.id} className="hover:bg-[#FFFDFD]">
                    <td className="py-3 px-4 font-mono font-bold text-elora-charcoal">
                      {b.batch_number}
                    </td>
                    <td className="py-3 px-4">
                      <span className="font-semibold text-elora-charcoal block line-clamp-1">
                        {prod ? prod.name_en : b.product_id}
                      </span>
                      <span className="text-[10px] text-[#7A7072]">{b.supplier_id || 'Direct Import'}</span>
                    </td>
                    <td className="py-3 px-4">
                      <span className="font-bold text-elora-charcoal text-sm">
                        {b.available_quantity}
                      </span>
                      <span className="text-[10px] text-[#7A7072] ml-1">/ {b.initial_quantity}</span>
                    </td>
                    <td className="py-3 px-4 font-mono">
                      ৳{b.purchase_cost.toLocaleString()}
                    </td>
                    <td className="py-3 px-4">
                      <span className={`font-bold ${isNear ? 'text-amber-700' : 'text-emerald-700'}`}>
                        {b.expiry_date}
                      </span>
                      {isNear && (
                        <span className="text-[9px] text-amber-700 block font-medium">Near Expiry (&lt; 180d)</span>
                      )}
                    </td>
                    <td className="py-3 px-4">
                      <span className={`px-2 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                        b.status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'
                      }`}>
                        {b.status}
                      </span>
                    </td>
                    <td className="py-3 px-4 text-right">
                      <div className="flex items-center justify-end gap-1">
                        <button
                          onClick={() => updateBatchQuantity(b.id, 5)}
                          className="px-2 py-1 rounded bg-[#FFF9F2] hover:bg-[#FDF2F3] text-elora-charcoal text-[11px] font-bold border border-[#EEDAD9]"
                          title="Add 5 units"
                        >
                          +5
                        </button>
                        <button
                          onClick={() => updateBatchQuantity(b.id, -1)}
                          className="px-2 py-1 rounded bg-[#FFF9F2] hover:bg-rose-50 text-rose-700 text-[11px] font-bold border border-[#EEDAD9]"
                          title="Deduct 1 unit"
                        >
                          -1
                        </button>
                      </div>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal: Receive Stock */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
          <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-2xl max-w-xl w-full p-6 sm:p-8 space-y-4">
            <h3 className="font-serif text-lg font-bold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
              Receive New Stock Batch (FEFO Integrated)
            </h3>

            <form onSubmit={handleCreateBatch} className="space-y-4 text-xs">
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="font-semibold block mb-1">Batch Number *</label>
                  <input
                    type="text"
                    required
                    value={batchNumber}
                    onChange={(e) => setBatchNumber(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl font-mono outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Target Product *</label>
                  <select
                    value={productId}
                    onChange={(e) => setProductId(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  >
                    {products.map((p) => (
                      <option key={p.id} value={p.id}>{p.name_en}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="font-semibold block mb-1">Purchase Date</label>
                  <input
                    type="date"
                    value={purchaseDate}
                    onChange={(e) => setPurchaseDate(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Mfg Date</label>
                  <input
                    type="date"
                    value={mfgDate}
                    onChange={(e) => setMfgDate(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Expiry Date (FEFO) *</label>
                  <input
                    type="date"
                    required
                    value={expiryDate}
                    onChange={(e) => setExpiryDate(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2 rounded-xl outline-none font-bold text-[#B83B5E]"
                  />
                </div>
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="font-semibold block mb-1">Received Quantity (Units) *</label>
                  <input
                    type="number"
                    required
                    value={initialQty}
                    onChange={(e) => setInitialQty(Number(e.target.value))}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Unit Cost (BDT ৳) *</label>
                  <input
                    type="number"
                    required
                    value={costPrice}
                    onChange={(e) => setCostPrice(Number(e.target.value))}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
              </div>

              <div>
                <label className="font-semibold block mb-1">Supplier / Logistics Partner</label>
                <input
                  type="text"
                  value={supplierId}
                  onChange={(e) => setSupplierId(e.target.value)}
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
                  Confirm & Receive Batch
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
