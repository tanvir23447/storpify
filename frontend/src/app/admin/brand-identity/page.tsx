'use client';

import React, { useState, useRef } from 'react';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { AssetType, BrandAsset } from '@/types';
import { Logo } from '@/components/brand/Logo';
import {
  Upload,
  RefreshCw,
  RotateCcw,
  CheckCircle2,
  Trash2,
  Eye,
  Sliders,
  Image as ImageIcon,
  Sparkles,
  AlertCircle,
  FileCheck,
  Smartphone,
  Monitor,
  Share2
} from 'lucide-react';

const VARIATION_TABS: { type: AssetType; label: string; desc: string }[] = [
  { type: 'primary', label: 'Primary Website Logo', desc: 'Main brand logo displayed on homepage and primary desktop header.' },
  { type: 'compact', label: 'Compact Header Logo', desc: 'Optimized square/compact logo for sticky navigation.' },
  { type: 'mobile', label: 'Mobile Header Logo', desc: 'Scaled logo specifically tuned for smartphone screens.' },
  { type: 'footer', label: 'Footer Luxury Logo', desc: 'Emblem rendered on dark/charcoal luxury footer.' },
  { type: 'admin', label: 'Super Admin Logo', desc: 'Official insignia displayed in admin command center.' },
  { type: 'favicon', label: 'Browser Favicon', desc: '16x16 / 32x32 tab icon for browsers.' },
  { type: 'social', label: 'Social & Open Graph', desc: 'Preview image for Facebook, Instagram, and WhatsApp link sharing.' },
];

export default function BrandIdentityAndLogoPage() {
  const language = useEloraStore((state) => state.language);
  const brandAssets = useEloraStore((state) => state.brandAssets);
  const brandSettings = useEloraStore((state) => state.brandSettings);
  const updateBrandSettings = useEloraStore((state) => state.updateBrandSettings);
  const uploadBrandAsset = useEloraStore((state) => state.uploadBrandAsset);
  const setActiveLogo = useEloraStore((state) => state.setActiveLogo);
  const resetLogoToDefault = useEloraStore((state) => state.resetLogoToDefault);
  const restorePreviousLogo = useEloraStore((state) => state.restorePreviousLogo);
  const deleteBrandAsset = useEloraStore((state) => state.deleteBrandAsset);
  const getActiveLogo = useEloraStore((state) => state.getActiveLogo);

  const [selectedType, setSelectedType] = useState<AssetType>('primary');
  const [previewBg, setPreviewBg] = useState<'ivory' | 'charcoal' | 'blush'>('ivory');
  const [altTextInput, setAltTextInput] = useState('');
  const [feedback, setFeedback] = useState<{ type: 'success' | 'error'; message: string } | null>(null);

  // File upload input ref
  const fileInputRef = useRef<HTMLInputElement>(null);

  // Active logo for selected variation
  const activeAsset = getActiveLogo(selectedType);

  // History for selected variation
  const variationHistory = brandAssets.filter((a) => a.asset_type === selectedType);

  const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    // 1. File Type Validation
    const validMimes = ['image/png', 'image/jpeg', 'image/jpg', 'image/webp', 'image/svg+xml'];
    if (!validMimes.includes(file.type)) {
      setFeedback({
        type: 'error',
        message: 'Unsupported format. Please upload PNG, JPG, JPEG, or WebP.',
      });
      return;
    }

    // 2. File Size Validation (Max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      setFeedback({
        type: 'error',
        message: 'File too large. Maximum allowed logo size is 5MB.',
      });
      return;
    }

    // Read image using FileReader to create real data URL preview & dimensions
    const reader = new FileReader();
    reader.onload = (event) => {
      const dataUrl = event.target?.result as string;
      const img = new (window as any).Image();
      img.src = dataUrl;
      img.onload = () => {
        // Upload into store
        const newAsset = uploadBrandAsset({
          asset_type: selectedType,
          asset_name: `${file.name.replace(/\.[^/.]+$/, '')} (${img.width}x${img.height})`,
          storage_path: `brand-assets/${selectedType}/${Date.now()}-${file.name}`,
          public_url_or_path: dataUrl,
          alt_text: altTextInput || `${brandSettings.brand_name} ${selectedType} logo`,
          width: img.width,
          height: img.height,
          mime_type: file.type,
          file_size: file.size,
          is_active: true,
          uploaded_by: 'Super Admin',
        });

        setFeedback({
          type: 'success',
          message: `Success! New ${selectedType} logo uploaded and activated across all pages without source code edits!`,
        });

        // Reset file input
        if (fileInputRef.current) fileInputRef.current.value = '';
      };
    };
    reader.readAsDataURL(file);
  };

  const handleResetToDefault = () => {
    resetLogoToDefault(selectedType);
    setFeedback({
      type: 'success',
      message: `Reset ${selectedType} logo to official Maison Elora default reference logo.`,
    });
  };

  const handleRestorePrevious = () => {
    restorePreviousLogo(selectedType);
    setFeedback({
      type: 'success',
      message: `Restored previous active logo version for ${selectedType}.`,
    });
  };

  return (
    <div className="space-y-8 max-w-7xl mx-auto">
      
      {/* Module Title Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div className="space-y-1">
          <div className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FDF2F3] text-[#B83B5E] text-xs font-bold border border-[#F0D8D9]">
            <Sparkles className="w-3.5 h-3.5 text-[#C9A45C]" />
            <span>Section 1 Master Feature</span>
          </div>
          <h1 className="font-serif text-2xl sm:text-3xl font-bold text-elora-charcoal">
            Brand Identity & Dynamic Logo Management
          </h1>
          <p className="text-xs sm:text-sm text-[#554B4E]">
            Replace, upload, preview, and restore website logos instantly. All changes propagate across the header, footer, mobile drawer, and admin dashboard without touching source code.
          </p>
        </div>

        <div className="flex items-center gap-3">
          <button
            onClick={handleResetToDefault}
            className="px-4 py-2 rounded-full border border-[#EEDAD9] hover:bg-[#FFF9F2] text-xs font-semibold text-elora-charcoal flex items-center gap-1.5 transition-colors"
          >
            <RotateCcw className="w-3.5 h-3.5 text-[#C9A45C]" />
            <span>Reset to Default</span>
          </button>
        </div>
      </div>

      {feedback && (
        <div className={`p-4 rounded-2xl text-xs sm:text-sm flex items-center gap-2 border ${
          feedback.type === 'success' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-rose-50 text-rose-800 border-rose-200'
        }`}>
          {feedback.type === 'success' ? <CheckCircle2 className="w-5 h-5 flex-shrink-0" /> : <AlertCircle className="w-5 h-5 flex-shrink-0" />}
          <span>{feedback.message}</span>
        </div>
      )}

      {/* Variation Selector Tabs */}
      <div className="flex gap-2 overflow-x-auto pb-2 border-b border-[#EEDAD9]">
        {VARIATION_TABS.map((tab) => (
          <button
            key={tab.type}
            onClick={() => {
              setSelectedType(tab.type);
              setFeedback(null);
            }}
            className={`px-4 py-2.5 rounded-2xl text-xs font-semibold whitespace-nowrap transition-all ${
              selectedType === tab.type
                ? 'bg-[#B83B5E] text-white shadow-xs'
                : 'bg-white text-elora-charcoal hover:bg-[#FDF2F3] border border-[#F0D8D9]'
            }`}
          >
            {tab.label}
          </button>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {/* Left Column: Live Previews & Test Renders (Col 7) */}
        <div className="lg:col-span-7 space-y-6">
          <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-6">
            <div className="flex flex-wrap items-center justify-between gap-3 border-b border-[#F0D8D9] pb-4">
              <div>
                <h3 className="font-serif text-lg font-semibold text-elora-charcoal">
                  Current Active: {VARIATION_TABS.find(t => t.type === selectedType)?.label}
                </h3>
                <p className="text-xs text-[#7A7072]">
                  {VARIATION_TABS.find(t => t.type === selectedType)?.desc}
                </p>
              </div>

              {/* Background Contrast Preview Switcher */}
              <div className="flex items-center gap-1.5 text-xs bg-[#FFF9F2] p-1 rounded-xl border border-[#EEDAD9]">
                <span className="text-[10px] text-[#7A7072] px-1 font-semibold">Contrast:</span>
                <button
                  onClick={() => setPreviewBg('ivory')}
                  className={`w-6 h-6 rounded-lg border text-[10px] font-bold ${
                    previewBg === 'ivory' ? 'bg-[#FFF9F2] border-[#C9A45C] ring-1 ring-[#C9A45C]' : 'bg-[#FFF9F2] border-transparent'
                  }`}
                  title="Soft Ivory"
                />
                <button
                  onClick={() => setPreviewBg('blush')}
                  className={`w-6 h-6 rounded-lg border text-[10px] font-bold ${
                    previewBg === 'blush' ? 'bg-[#F6D7D9] border-[#C9A45C] ring-1 ring-[#C9A45C]' : 'bg-[#F6D7D9] border-transparent'
                  }`}
                  title="Blush Pink"
                />
                <button
                  onClick={() => setPreviewBg('charcoal')}
                  className={`w-6 h-6 rounded-lg border text-[10px] font-bold ${
                    previewBg === 'charcoal' ? 'bg-[#292426] border-[#C9A45C] ring-1 ring-[#C9A45C]' : 'bg-[#292426] border-transparent'
                  }`}
                  title="Deep Charcoal"
                />
              </div>
            </div>

            {/* Live Interactive Rendering Box */}
            <div className={`p-8 sm:p-12 rounded-2xl flex flex-col items-center justify-center border transition-all ${
              previewBg === 'ivory' ? 'bg-[#FFF9F2] border-[#EEDAD9]' : previewBg === 'blush' ? 'bg-[#F6D7D9] border-[#E4C1C4]' : 'bg-[#292426] border-[#44383B]'
            }`}>
              <div className="p-4 bg-white/40 backdrop-blur-xs rounded-2xl border border-white/60 shadow-sm">
                <Logo
                  variant={selectedType}
                  width={selectedType === 'favicon' ? 32 : selectedType === 'mobile' ? 44 : 64}
                  height={selectedType === 'favicon' ? 32 : selectedType === 'mobile' ? 44 : 64}
                />
              </div>
              <span className={`text-[11px] mt-4 font-mono ${previewBg === 'charcoal' ? 'text-white/70' : 'text-[#7A7072]'}`}>
                Path: {activeAsset.public_url_or_path}
              </span>
            </div>

            {/* Asset Metadata Grid */}
            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 p-4 rounded-2xl bg-[#FFF9F2] border border-[#EEDAD9] text-xs">
              <div>
                <span className="text-[#7A7072] block">Resolution:</span>
                <span className="font-mono font-bold text-elora-charcoal">
                  {activeAsset.width ? `${activeAsset.width} × ${activeAsset.height} px` : 'Scalable / Vector'}
                </span>
              </div>
              <div>
                <span className="text-[#7A7072] block">MIME Type:</span>
                <span className="font-mono font-bold text-elora-charcoal">{activeAsset.mime_type || 'image/jpeg'}</span>
              </div>
              <div>
                <span className="text-[#7A7072] block">File Size:</span>
                <span className="font-bold text-elora-charcoal">
                  {activeAsset.file_size ? `${Math.round(activeAsset.file_size / 1024)} KB` : '326 KB'}
                </span>
              </div>
              <div>
                <span className="text-[#7A7072] block">Status:</span>
                <span className="font-bold text-emerald-700 uppercase">Live Published</span>
              </div>
            </div>
          </div>

          {/* Desktop vs Mobile Header Simulation Preview */}
          <div className="bg-white p-6 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-4">
            <h4 className="font-serif text-sm font-semibold text-elora-charcoal flex items-center gap-2">
              <Monitor className="w-4 h-4 text-[#C9A45C]" />
              <span>Simulated Website Header Integration</span>
            </h4>

            {/* Header Mockup */}
            <div className="border border-[#EEDAD9] rounded-2xl overflow-hidden shadow-xs">
              <div className="bg-[#B83B5E] text-white text-[10px] py-1 px-3 flex justify-between font-medium">
                <span>Free Delivery on Orders Over ৳2,000</span>
                <span>English | বাংলা</span>
              </div>
              <div className="bg-[#FFF9F2] p-3 flex items-center justify-between border-b border-[#EEDAD9]">
                <Logo variant={selectedType === 'mobile' ? 'mobile' : 'compact'} />
                <div className="w-36 h-7 rounded-full bg-white border border-[#EEDAD9] hidden sm:block" />
                <div className="flex gap-2">
                  <div className="w-6 h-6 rounded-full bg-white border border-[#EEDAD9]" />
                  <div className="w-6 h-6 rounded-full bg-white border border-[#EEDAD9]" />
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Upload Tool & Version History (Col 5) */}
        <div className="lg:col-span-5 space-y-6">
          
          {/* Upload New File Box */}
          <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-5">
            <h3 className="font-serif text-lg font-semibold text-elora-charcoal border-b border-[#F0D8D9] pb-3 flex items-center gap-2">
              <Upload className="w-4 h-4 text-[#B83B5E]" />
              <span>Upload / Replace {VARIATION_TABS.find(t => t.type === selectedType)?.label}</span>
            </h3>

            {/* Hidden file input */}
            <input
              type="file"
              ref={fileInputRef}
              accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
              onChange={handleFileUpload}
              className="hidden"
            />

            {/* Dropzone Area */}
            <div
              onClick={() => fileInputRef.current?.click()}
              className="border-2 border-dashed border-[#C9A45C]/60 hover:border-[#B83B5E] bg-[#FFF9F2] hover:bg-[#FDF2F3] rounded-2xl p-6 text-center cursor-pointer transition-all space-y-3 group"
            >
              <div className="w-12 h-12 rounded-full bg-white border border-[#C9A45C]/40 flex items-center justify-center mx-auto text-[#C9A45C] group-hover:text-[#B83B5E] transition-colors shadow-xs">
                <Upload className="w-6 h-6" />
              </div>
              <div>
                <span className="text-xs sm:text-sm font-bold text-elora-charcoal block">
                  Click to Browse or Drop Logo File
                </span>
                <span className="text-[11px] text-[#7A7072] block mt-1">
                  Supports PNG, JPG, WebP, SVG (Transparent background recommended, max 5MB)
                </span>
              </div>
            </div>

            {/* Alt Text Input */}
            <div className="space-y-1 text-xs">
              <label className="font-semibold text-elora-charcoal block">
                Accessibility Alt Text (SEO & Screen Readers)
              </label>
              <input
                type="text"
                value={altTextInput}
                onChange={(e) => setAltTextInput(e.target.value)}
                placeholder="e.g. Maison Elora Luxury Golden Monogram"
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] focus:border-[#B83B5E] rounded-xl px-3 py-2 text-xs text-elora-charcoal outline-none"
              />
            </div>
          </div>

          {/* Logo History & Restore (Section 1.5) */}
          <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-4">
            <div className="flex items-center justify-between border-b border-[#F0D8D9] pb-3">
              <h3 className="font-serif text-base font-semibold text-elora-charcoal flex items-center gap-2">
                <RotateCcw className="w-4 h-4 text-[#C9A45C]" />
                <span>Version History ({variationHistory.length})</span>
              </h3>
              {variationHistory.length > 1 && (
                <button
                  onClick={handleRestorePrevious}
                  className="text-xs font-semibold text-[#B83B5E] hover:underline"
                >
                  Restore Previous
                </button>
              )}
            </div>

            <div className="space-y-3 max-h-72 overflow-y-auto pr-1">
              {variationHistory.map((asset) => (
                <div
                  key={asset.id}
                  className={`p-3 rounded-xl border flex items-center justify-between gap-3 text-xs transition-all ${
                    asset.is_active
                      ? 'border-[#B83B5E] bg-[#FDF2F3]'
                      : 'border-[#EEDAD9] bg-[#FFF9F2] hover:bg-white'
                  }`}
                >
                  <div className="flex items-center gap-2.5 min-w-0">
                    <div className="relative w-9 h-9 rounded-full overflow-hidden bg-white border border-[#EEDAD9] flex-shrink-0">
                      <Image
                        src={asset.public_url_or_path}
                        alt="history thumbnail"
                        fill
                        className="object-cover"
                      />
                    </div>
                    <div className="min-w-0">
                      <span className="font-semibold text-elora-charcoal truncate block">
                        {asset.asset_name}
                      </span>
                      <span className="text-[10px] text-[#7A7072]">
                        {new Date(asset.created_at).toLocaleDateString()} • {asset.uploaded_by || 'Admin'}
                      </span>
                    </div>
                  </div>

                  <div className="flex items-center gap-1.5 flex-shrink-0">
                    {asset.is_active ? (
                      <span className="px-2 py-0.5 rounded-full bg-[#B83B5E] text-white text-[10px] font-bold">
                        Active
                      </span>
                    ) : (
                      <>
                        <button
                          onClick={() => {
                            setActiveLogo(asset.id, selectedType);
                            setFeedback({
                              type: 'success',
                              message: `Activated logo version: ${asset.asset_name}`,
                            });
                          }}
                          className="px-2.5 py-1 rounded-lg bg-white border border-[#EEDAD9] hover:bg-[#FDF2F3] text-elora-charcoal font-semibold text-[10px]"
                        >
                          Activate
                        </button>
                        <button
                          onClick={() => deleteBrandAsset(asset.id)}
                          className="p-1 text-[#7A7072] hover:text-rose-600"
                          title="Delete unused logo"
                        >
                          <Trash2 className="w-3.5 h-3.5" />
                        </button>
                      </>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
