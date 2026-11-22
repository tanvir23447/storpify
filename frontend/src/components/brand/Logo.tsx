'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { useEloraStore } from '@/store/eloraStore';
import { AssetType } from '@/types';

interface LogoProps {
  variant?: AssetType;
  className?: string;
  showTagline?: boolean;
  href?: string;
  width?: number;
  height?: number;
  priority?: boolean;
}

export const Logo: React.FC<LogoProps> = ({
  variant = 'primary',
  className = '',
  showTagline = true,
  href = '/',
  width,
  height,
  priority = true,
}) => {
  const [mounted, setMounted] = useState(false);
  const getActiveLogo = useEloraStore((state) => state.getActiveLogo);
  const brandSettings = useEloraStore((state) => state.brandSettings);
  const language = useEloraStore((state) => state.language);

  useEffect(() => {
    setMounted(true);
  }, []);

  const activeAsset = mounted ? getActiveLogo(variant) : null;
  
  // By default for header/primary, use the exact horizontal reference lockup
  const defaultLogoPath = variant === 'footer' || variant === 'admin'
    ? '/images/brand/maison-elora-logo.jpg'
    : '/images/brand/header-logo-reference.jpg';

  const logoUrl = activeAsset?.public_url_or_path && activeAsset.public_url_or_path !== '/images/brand/maison-elora-logo.jpg'
    ? activeAsset.public_url_or_path
    : defaultLogoPath;

  const altText = activeAsset?.alt_text || 'Maison Elora - Premium Beauty & Care';

  const content = (
    <div className={`inline-flex items-center gap-2 select-none ${className}`}>
      {logoUrl.includes('header-logo-reference') ? (
        <div className="relative h-10 w-[180px] sm:w-[195px] flex items-center">
          <Image
            src={logoUrl}
            alt={altText}
            width={195}
            height={42}
            className="object-contain object-left"
            priority={priority}
          />
        </div>
      ) : (
        <div className="flex items-center gap-2.5">
          <div className="relative w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-[#C9A45C]/40">
            <Image
              src={logoUrl}
              alt={altText}
              fill
              className="object-cover"
              priority={priority}
            />
          </div>
          <div className="flex flex-col leading-tight">
            <span className="font-serif tracking-wide text-maison-charcoal font-semibold text-lg sm:text-xl">
              {brandSettings?.brand_name || 'Maison Elora'}
            </span>
            {showTagline && (
              <span className="text-[10px] uppercase tracking-[0.18em] text-[#C9A45C] font-medium font-sans">
                {language === 'bn' ? 'প্রিমিয়াম বিউটি অ্যান্ড কেয়ার' : (brandSettings?.tagline || 'Premium Beauty & Care')}
              </span>
            )}
          </div>
        </div>
      )}
    </div>
  );

  if (href) {
    return (
      <Link href={href} className="focus:outline-none rounded-lg inline-block">
        {content}
      </Link>
    );
  }

  return content;
};
