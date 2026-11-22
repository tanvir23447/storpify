'use client';

import React, { useState } from 'react';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { Product } from '@/types';
import { Plus, Edit, Trash2, Search, Check, Sparkles, Filter, Package, AlertCircle } from 'lucide-react';

export default function AdminProductsPage() {
  const products = useEloraStore((state) => state.products);
  const categories = useEloraStore((state) => state.categories);
  const addProduct = useEloraStore((state) => state.addProduct);
  const updateProduct = useEloraStore((state) => state.updateProduct);
  const deleteProduct = useEloraStore((state) => state.deleteProduct);

  const [search, setSearch] = useState('');
  const [selectedCat, setSelectedCat] = useState('all');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingId, setEditingId] = useState<string | null>(null);

  // Form State
  const [nameEn, setNameEn] = useState('');
  const [nameBn, setNameBn] = useState('');
  const [sku, setSku] = useState('');
  const [price, setPrice] = useState(1500);
  const [comparePrice, setComparePrice] = useState(1800);
  const [categoryId, setCategoryId] = useState('cat-skincare');
  const [brandId, setBrandId] = useState('The Ordinary');
  const [volumeWeight, setVolumeWeight] = useState('50 ml');
  const [countryOfOrigin, setCountryOfOrigin] = useState('France');
  const [shortDesc, setShortDesc] = useState('');
  const [imageUrl, setImageUrl] = useState('');
  const [isNewArrival, setIsNewArrival] = useState(true);
  const [isBestSeller, setIsBestSeller] = useState(false);

  const openCreateModal = () => {
    setEditingId(null);
    setNameEn('');
    setNameBn('');
    setSku(`SKU-${Date.now().toString().slice(-6)}`);
    setPrice(1950);
    setComparePrice(2200);
    setCategoryId(categories[0]?.id || 'cat-skincare');
    setBrandId('Maison Elora');
    setVolumeWeight('50 ml');
    setCountryOfOrigin('France');
    setShortDesc('');
    setImageUrl('https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=800&q=80');
    setIsNewArrival(true);
    setIsBestSeller(false);
    setIsModalOpen(true);
  };

  const openEditModal = (p: Product) => {
    setEditingId(p.id);
    setNameEn(p.name_en);
    setNameBn(p.name_bn);
    setSku(p.sku);
    setPrice(p.price);
    setComparePrice(p.compare_at_price || p.price);
    setCategoryId(p.category_id);
    setBrandId(p.brand_id || 'Maison Elora');
    setVolumeWeight(p.volume_weight || '');
    setCountryOfOrigin(p.country_of_origin || '');
    setShortDesc(p.short_description_en || '');
    setImageUrl(p.images[0] || '');
    setIsNewArrival(p.is_new_arrival);
    setIsBestSeller(p.is_best_seller);
    setIsModalOpen(true);
  };

  const handleSaveProduct = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingId) {
      updateProduct(editingId, {
        name_en: nameEn,
        name_bn: nameBn,
        sku,
        price: Number(price),
        compare_at_price: Number(comparePrice),
        category_id: categoryId,
        brand_id: brandId,
        volume_weight: volumeWeight,
        country_of_origin: countryOfOrigin,
        short_description_en: shortDesc,
        images: [imageUrl],
        is_new_arrival: isNewArrival,
        is_best_seller: isBestSeller,
      });
    } else {
      addProduct({
        name_en: nameEn,
        name_bn: nameBn,
        slug: nameEn.toLowerCase().replace(/[^a-z0-9]+/g, '-'),
        sku,
        price: Number(price),
        compare_at_price: Number(comparePrice),
        category_id: categoryId,
        brand_id: brandId,
        volume_weight: volumeWeight,
        country_of_origin: countryOfOrigin,
        short_description_en: shortDesc,
        images: [imageUrl],
        is_new_arrival: isNewArrival,
        is_best_seller: isBestSeller,
        is_featured: true,
        rating: 5.0,
        review_count: 1,
      });
    }
    setIsModalOpen(false);
  };

  const filtered = products.filter((p) => {
    if (search) {
      const q = search.toLowerCase();
      if (!p.name_en.toLowerCase().includes(q) && !p.sku.toLowerCase().includes(q)) return false;
    }
    if (selectedCat !== 'all' && p.category_id !== selectedCat) return false;
    return true;
  });

  return (
    <div className="space-y-6 max-w-7xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">Catalog & Products</span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Product Management ({products.length})
          </h1>
          <p className="text-xs text-[#554B4E]">Manage pricing, descriptions, SKU, and imported product attributes.</p>
        </div>

        <button
          onClick={openCreateModal}
          className="px-5 py-2.5 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white text-xs font-semibold flex items-center gap-2 shadow-sm transition-all"
        >
          <Plus className="w-4 h-4" />
          <span>Add New Luxury Product</span>
        </button>
      </div>

      {/* Filter / Search Bar */}
      <div className="bg-white p-4 rounded-2xl border border-[#F0D8D9] shadow-xs flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="relative w-full sm:w-80">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search by product name or SKU..."
            className="w-full bg-[#FFF9F2] border border-[#EEDAD9] rounded-xl px-3.5 py-2 text-xs text-elora-charcoal outline-none focus:border-[#B83B5E]"
          />
          <Search className="w-3.5 h-3.5 text-[#7A7072] absolute right-3 top-3" />
        </div>

        <select
          value={selectedCat}
          onChange={(e) => setSelectedCat(e.target.value)}
          className="w-full sm:w-auto bg-[#FFF9F2] border border-[#EEDAD9] rounded-xl px-3 py-2 text-xs text-elora-charcoal outline-none"
        >
          <option value="all">All Categories</option>
          {categories.map((c) => (
            <option key={c.id} value={c.id}>{c.name_en}</option>
          ))}
        </select>
      </div>

      {/* Table */}
      <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-xs overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-[#FFF9F2] text-[#7A7072] uppercase font-bold text-[10px] tracking-wider border-b border-[#EEDAD9]">
              <tr>
                <th className="py-3 px-4">Item</th>
                <th className="py-3 px-4">Brand / Origin</th>
                <th className="py-3 px-4">SKU</th>
                <th className="py-3 px-4">Price</th>
                <th className="py-3 px-4">Tags</th>
                <th className="py-3 px-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-[#F0D8D9]">
              {filtered.map((p) => (
                <tr key={p.id} className="hover:bg-[#FFFDFD]">
                  <td className="py-3 px-4">
                    <div className="flex items-center gap-3">
                      <div className="relative w-10 h-10 rounded-lg overflow-hidden bg-[#FFF9F2] border border-[#EEDAD9] flex-shrink-0">
                        <Image src={p.images[0]} alt={p.name_en} fill className="object-contain p-1" />
                      </div>
                      <div>
                        <span className="font-semibold text-elora-charcoal block line-clamp-1">{p.name_en}</span>
                        <span className="text-[10px] text-[#7A7072]">{p.volume_weight || 'Standard'}</span>
                      </div>
                    </div>
                  </td>
                  <td className="py-3 px-4">
                    <span className="font-medium text-elora-charcoal block">{p.brand_id || 'Maison Elora'}</span>
                    <span className="text-[10px] text-[#7A7072]">{p.country_of_origin || 'Imported'}</span>
                  </td>
                  <td className="py-3 px-4 font-mono font-bold text-elora-charcoal">{p.sku}</td>
                  <td className="py-3 px-4">
                    <span className="font-bold text-[#B83B5E] block">৳{p.price.toLocaleString()}</span>
                    {p.compare_at_price && p.compare_at_price > p.price && (
                      <span className="text-[10px] text-[#7A7072] line-through">৳{p.compare_at_price.toLocaleString()}</span>
                    )}
                  </td>
                  <td className="py-3 px-4">
                    <div className="flex gap-1 flex-wrap">
                      {p.is_new_arrival && (
                        <span className="px-1.5 py-0.5 rounded bg-[#FDF2F3] text-[#B83B5E] text-[9px] font-bold">New</span>
                      )}
                      {p.is_best_seller && (
                        <span className="px-1.5 py-0.5 rounded bg-[#C9A45C]/20 text-[#A68238] text-[9px] font-bold">Best Seller</span>
                      )}
                    </div>
                  </td>
                  <td className="py-3 px-4 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button
                        onClick={() => openEditModal(p)}
                        className="p-1.5 text-elora-charcoal hover:text-[#B83B5E] rounded-lg hover:bg-[#FDF2F3]"
                        title="Edit Product"
                      >
                        <Edit className="w-3.5 h-3.5" />
                      </button>
                      <button
                        onClick={() => {
                          if (confirm(`Delete "${p.name_en}"?`)) deleteProduct(p.id);
                        }}
                        className="p-1.5 text-elora-charcoal hover:text-rose-600 rounded-lg hover:bg-rose-50"
                        title="Delete Product"
                      >
                        <Trash2 className="w-3.5 h-3.5" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal: Create / Edit Product */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs">
          <div className="bg-white rounded-3xl border border-[#F0D8D9] shadow-2xl max-w-2xl w-full p-6 sm:p-8 space-y-4 max-h-[90vh] overflow-y-auto">
            <h3 className="font-serif text-lg font-bold text-elora-charcoal border-b border-[#F0D8D9] pb-3">
              {editingId ? 'Edit Product' : 'Add New Luxury Beauty Product'}
            </h3>

            <form onSubmit={handleSaveProduct} className="space-y-4 text-xs">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label className="font-semibold block mb-1">Product Name (English) *</label>
                  <input
                    type="text"
                    required
                    value={nameEn}
                    onChange={(e) => setNameEn(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Product Name (Bengali)</label>
                  <input
                    type="text"
                    value={nameBn}
                    onChange={(e) => setNameBn(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="font-semibold block mb-1">SKU *</label>
                  <input
                    type="text"
                    required
                    value={sku}
                    onChange={(e) => setSku(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl font-mono outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Price (৳) *</label>
                  <input
                    type="number"
                    required
                    value={price}
                    onChange={(e) => setPrice(Number(e.target.value))}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Compare Price (৳)</label>
                  <input
                    type="number"
                    value={comparePrice}
                    onChange={(e) => setComparePrice(Number(e.target.value))}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="font-semibold block mb-1">Category</label>
                  <select
                    value={categoryId}
                    onChange={(e) => setCategoryId(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  >
                    {categories.map((c) => (
                      <option key={c.id} value={c.id}>{c.name_en}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="font-semibold block mb-1">Brand</label>
                  <input
                    type="text"
                    value={brandId}
                    onChange={(e) => setBrandId(e.target.value)}
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
                <div>
                  <label className="font-semibold block mb-1">Volume / Weight</label>
                  <input
                    type="text"
                    value={volumeWeight}
                    onChange={(e) => setVolumeWeight(e.target.value)}
                    placeholder="e.g. 30 ml, 100 ml"
                    className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                  />
                </div>
              </div>

              <div>
                <label className="font-semibold block mb-1">Image URL</label>
                <input
                  type="url"
                  required
                  value={imageUrl}
                  onChange={(e) => setImageUrl(e.target.value)}
                  className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                />
              </div>

              <div>
                <label className="font-semibold block mb-1">Short Description</label>
                <textarea
                  rows={2}
                  value={shortDesc}
                  onChange={(e) => setShortDesc(e.target.value)}
                  className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
                />
              </div>

              <div className="flex gap-4 pt-2">
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={isNewArrival}
                    onChange={(e) => setIsNewArrival(e.target.checked)}
                    className="accent-[#B83B5E]"
                  />
                  <span>Mark as New Arrival</span>
                </label>
                <label className="flex items-center gap-2 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={isBestSeller}
                    onChange={(e) => setIsBestSeller(e.target.checked)}
                    className="accent-[#B83B5E]"
                  />
                  <span>Mark as Best Seller</span>
                </label>
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
                  Save Product
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}
