'use client';

import React, { useState } from 'react';
import { useEloraStore } from '@/store/eloraStore';
import { Sliders, Save, CheckCircle2, Globe, Sparkles } from 'lucide-react';

export default function AdminSettingsPage() {
  const brandSettings = useEloraStore((state) => state.brandSettings);
  const updateBrandSettings = useEloraStore((state) => state.updateBrandSettings);

  const [brandName, setBrandName] = useState(brandSettings.brand_name);
  const [tagline, setTagline] = useState(brandSettings.tagline);
  const [announcementEn, setAnnouncementEn] = useState(brandSettings.header_announcement_en);
  const [announcementBn, setAnnouncementBn] = useState(brandSettings.header_announcement_bn);
  const [contactPhone, setContactPhone] = useState(brandSettings.contact_phone);
  const [contactEmail, setContactEmail] = useState(brandSettings.contact_email);
  const [whatsapp, setWhatsapp] = useState(brandSettings.whatsapp_number);
  const [address, setAddress] = useState(brandSettings.business_address);
  const [facebookUrl, setFacebookUrl] = useState(brandSettings.facebook_url);
  const [instagramUrl, setInstagramUrl] = useState(brandSettings.instagram_url);

  const [saved, setSaved] = useState(false);

  const handleSave = (e: React.FormEvent) => {
    e.preventDefault();
    updateBrandSettings({
      brand_name: brandName,
      tagline,
      header_announcement_en: announcementEn,
      header_announcement_bn: announcementBn,
      contact_phone: contactPhone,
      contact_email: contactEmail,
      whatsapp_number: whatsapp,
      business_address: address,
      facebook_url: facebookUrl,
      instagram_url: instagramUrl,
    });
    setSaved(true);
    setTimeout(() => setSaved(false), 3000);
  };

  return (
    <div className="space-y-6 max-w-4xl mx-auto">
      {/* Top Banner */}
      <div className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs flex items-center justify-between">
        <div>
          <span className="text-xs font-bold text-[#C9A45C] uppercase tracking-wider">Dynamic Branding</span>
          <h1 className="font-serif text-2xl font-bold text-elora-charcoal">
            Website & Global Brand Settings
          </h1>
          <p className="text-xs text-[#554B4E]">Configure live announcement bar, business address, and contact hotlines.</p>
        </div>
      </div>

      {saved && (
        <div className="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center gap-2">
          <CheckCircle2 className="w-5 h-5 flex-shrink-0" />
          <span>Brand Settings successfully saved and updated across all storefront views!</span>
        </div>
      )}

      <form onSubmit={handleSave} className="bg-white p-6 sm:p-8 rounded-3xl border border-[#F0D8D9] shadow-xs space-y-6 text-xs">
        {/* Brand Name & Tagline */}
        <div className="space-y-4 border-b border-[#F0D8D9] pb-6">
          <h3 className="font-serif text-base font-bold text-elora-charcoal">Brand Nomenclature</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="font-semibold block mb-1">Brand Name *</label>
              <input
                type="text"
                required
                value={brandName}
                onChange={(e) => setBrandName(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
            <div>
              <label className="font-semibold block mb-1">Tagline *</label>
              <input
                type="text"
                required
                value={tagline}
                onChange={(e) => setTagline(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
          </div>
        </div>

        {/* Announcement Bar */}
        <div className="space-y-4 border-b border-[#F0D8D9] pb-6">
          <h3 className="font-serif text-base font-bold text-elora-charcoal">Top Announcement Strip Text</h3>
          <div className="space-y-3">
            <div>
              <label className="font-semibold block mb-1">Announcement (English) *</label>
              <input
                type="text"
                required
                value={announcementEn}
                onChange={(e) => setAnnouncementEn(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
            <div>
              <label className="font-semibold block mb-1">Announcement (Bengali) *</label>
              <input
                type="text"
                required
                value={announcementBn}
                onChange={(e) => setAnnouncementBn(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
          </div>
        </div>

        {/* Contact & Support */}
        <div className="space-y-4 border-b border-[#F0D8D9] pb-6">
          <h3 className="font-serif text-base font-bold text-elora-charcoal">Official Contact Details</h3>
          <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label className="font-semibold block mb-1">Support Phone</label>
              <input
                type="text"
                value={contactPhone}
                onChange={(e) => setContactPhone(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
            <div>
              <label className="font-semibold block mb-1">Contact Email</label>
              <input
                type="email"
                value={contactEmail}
                onChange={(e) => setContactEmail(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
            <div>
              <label className="font-semibold block mb-1">WhatsApp Business</label>
              <input
                type="text"
                value={whatsapp}
                onChange={(e) => setWhatsapp(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
          </div>

          <div>
            <label className="font-semibold block mb-1">Physical Business Address</label>
            <input
              type="text"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
            />
          </div>
        </div>

        {/* Social Media Links */}
        <div className="space-y-4">
          <h3 className="font-serif text-base font-bold text-elora-charcoal">Official Social Channels</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="font-semibold block mb-1">Facebook Page URL</label>
              <input
                type="url"
                value={facebookUrl}
                onChange={(e) => setFacebookUrl(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
            <div>
              <label className="font-semibold block mb-1">Instagram Profile URL</label>
              <input
                type="url"
                value={instagramUrl}
                onChange={(e) => setInstagramUrl(e.target.value)}
                className="w-full bg-[#FFF9F2] border border-[#EEDAD9] p-2.5 rounded-xl outline-none"
              />
            </div>
          </div>
        </div>

        <div className="flex justify-end pt-4">
          <button
            type="submit"
            className="px-8 py-3 rounded-full bg-[#B83B5E] hover:bg-[#982845] text-white font-semibold text-xs flex items-center gap-2 shadow-md"
          >
            <Save className="w-4 h-4" />
            <span>Publish Brand Settings</span>
          </button>
        </div>
      </form>
    </div>
  );
}
