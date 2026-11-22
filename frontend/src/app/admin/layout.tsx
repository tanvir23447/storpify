'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { Logo } from '@/components/brand/Logo';
import { useEloraStore } from '@/store/eloraStore';
import {
  LayoutDashboard,
  Sparkles,
  Package,
  Layers,
  Boxes,
  ShoppingBag,
  CreditCard,
  Users,
  Tag,
  Sliders,
  ShieldCheck,
  ArrowLeft,
  Menu,
  X,
  Bell,
  Globe
} from 'lucide-react';

export default function AdminLayout({ children }: { children: React.ReactNode }) {
  const pathname = usePathname();
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const language = useEloraStore((state) => state.language);
  const setLanguage = useEloraStore((state) => state.setLanguage);
  const inventoryBatches = useEloraStore((state) => state.inventoryBatches);
  const orders = useEloraStore((state) => state.orders);

  // Near expiry alert count (< 90 days or status != active)
  const nearExpiryCount = inventoryBatches.filter(
    (b) => b.status === 'near_expiry' || (new Date(b.expiry_date).getTime() - Date.now() < 90 * 86400000)
  ).length;

  const pendingPaymentsCount = orders.filter((o) => o.payment_status === 'submitted').length;

  const navItems = [
    { label: 'Overview', href: '/admin', icon: LayoutDashboard },
    { label: 'Brand & Logo Suite', href: '/admin/brand-identity', icon: Sparkles, badge: 'Crucial' },
    { label: 'Products & Catalog', href: '/admin/products', icon: Package },
    { label: 'Inventory & FEFO Batches', href: '/admin/inventory', icon: Boxes, badgeCount: nearExpiryCount },
    { label: 'Orders Management', href: '/admin/orders', icon: ShoppingBag, badgeCount: orders.filter(o => o.order_status === 'pending').length },
    { label: 'Payment Verification', href: '/admin/payments', icon: CreditCard, badgeCount: pendingPaymentsCount },
    { label: 'Coupons & Discounts', href: '/admin/coupons', icon: Tag },
    { label: 'Brand & Site Settings', href: '/admin/settings', icon: Sliders },
  ];

  return (
    <div className="min-h-screen flex bg-[#FDF8F5] text-elora-charcoal">
      
      {/* Sidebar for Desktop */}
      <aside className="hidden lg:flex flex-col w-64 bg-white border-r border-[#EEDAD9] flex-shrink-0 z-30">
        {/* Admin Brand Logo Header */}
        <div className="p-5 border-b border-[#EEDAD9] bg-[#FFF9F2] flex items-center justify-between">
          <Logo variant="admin" href="/admin" showTagline={false} />
          <span className="text-[10px] font-bold text-[#C9A45C] bg-[#C9A45C]/15 px-2 py-0.5 rounded-full border border-[#C9A45C]/30 uppercase">
            Admin
          </span>
        </div>

        {/* Navigation Links */}
        <div className="flex-1 overflow-y-auto p-4 space-y-1 text-xs font-medium">
          <span className="text-[10px] font-bold text-[#7A7072] uppercase tracking-wider px-3 mb-2 block">
            Core Modules
          </span>

          {navItems.map((item) => {
            const Icon = item.icon;
            const isActive = pathname === item.href;
            return (
              <Link
                key={item.href}
                href={item.href}
                className={`flex items-center justify-between px-3 py-2.5 rounded-xl transition-all ${
                  isActive
                    ? 'bg-[#B83B5E] text-white font-semibold shadow-xs'
                    : 'text-elora-charcoal hover:bg-[#FDF2F3] hover:text-[#B83B5E]'
                }`}
              >
                <div className="flex items-center gap-2.5">
                  <Icon className={`w-4 h-4 ${isActive ? 'text-white' : 'text-[#C9A45C]'}`} />
                  <span>{item.label}</span>
                </div>
                {item.badge && (
                  <span className={`text-[10px] px-1.5 py-0.2 rounded font-bold ${
                    isActive ? 'bg-white/20 text-white' : 'bg-[#C9A45C]/15 text-[#C9A45C]'
                  }`}>
                    {item.badge}
                  </span>
                )}
                {item.badgeCount && item.badgeCount > 0 ? (
                  <span className="w-5 h-5 rounded-full bg-[#B83B5E] text-white text-[10px] font-bold flex items-center justify-center">
                    {item.badgeCount}
                  </span>
                ) : null}
              </Link>
            );
          })}
        </div>

        {/* Bottom Storefront Shortcut */}
        <div className="p-4 border-t border-[#EEDAD9] bg-[#FFF9F2]">
          <Link
            href="/"
            className="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-elora-charcoal hover:text-[#B83B5E] hover:bg-white transition-colors"
          >
            <ArrowLeft className="w-4 h-4 text-[#C9A45C]" />
            <span>Back to Live Website</span>
          </Link>
        </div>
      </aside>

      {/* Main Content Viewport */}
      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        {/* Admin Top Header */}
        <header className="h-16 bg-white border-b border-[#EEDAD9] flex items-center justify-between px-4 sm:px-8 z-20">
          <div className="flex items-center gap-3">
            <button
              onClick={() => setSidebarOpen(true)}
              className="lg:hidden p-2 text-elora-charcoal hover:text-[#B83B5E]"
            >
              <Menu className="w-5 h-5" />
            </button>
            <h2 className="font-serif text-lg font-semibold text-elora-charcoal hidden sm:block">
              Maison Elora Enterprise Command Center
            </h2>
          </div>

          <div className="flex items-center gap-4 text-xs">
            {/* Language Switch */}
            <button
              onClick={() => setLanguage(language === 'en' ? 'bn' : 'en')}
              className="flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-[#EEDAD9] hover:bg-[#FDF2F3]"
            >
              <Globe className="w-3.5 h-3.5 text-[#C9A45C]" />
              <span className="font-semibold uppercase">{language}</span>
            </button>

            {/* Admin Badge */}
            <div className="flex items-center gap-2 pl-2 border-l border-[#EEDAD9]">
              <div className="w-8 h-8 rounded-full bg-[#C9A45C]/20 border border-[#C9A45C] flex items-center justify-center text-[#C9A45C] font-bold">
                SA
              </div>
              <div className="hidden md:block leading-tight">
                <span className="font-bold text-elora-charcoal block">Super Admin</span>
                <span className="text-[10px] text-emerald-600 font-medium">Session Authenticated</span>
              </div>
            </div>
          </div>
        </header>

        {/* Content Body */}
        <main className="flex-1 overflow-y-auto p-4 sm:p-8">
          {children}
        </main>
      </div>

      {/* Mobile Drawer */}
      {sidebarOpen && (
        <div className="fixed inset-0 z-50 lg:hidden">
          <div className="fixed inset-0 bg-black/50" onClick={() => setSidebarOpen(false)} />
          <div className="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl flex flex-col z-50">
            <div className="p-4 border-b border-[#EEDAD9] flex items-center justify-between bg-[#FFF9F2]">
              <Logo variant="admin" href="/admin" showTagline={false} />
              <button onClick={() => setSidebarOpen(false)}>
                <X className="w-5 h-5 text-elora-charcoal" />
              </button>
            </div>
            <div className="p-4 space-y-1">
              {navItems.map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  onClick={() => setSidebarOpen(false)}
                  className="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-xs font-semibold text-elora-charcoal hover:bg-[#FDF2F3]"
                >
                  <item.icon className="w-4 h-4 text-[#C9A45C]" />
                  <span>{item.label}</span>
                </Link>
              ))}
              <div className="pt-4 border-t border-[#EEDAD9]">
                <Link
                  href="/"
                  className="flex items-center gap-2 px-3 py-2 text-xs font-semibold text-[#B83B5E]"
                >
                  <ArrowLeft className="w-4 h-4" />
                  <span>Back to Website</span>
                </Link>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
