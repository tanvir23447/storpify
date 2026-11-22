'use client';

import React from 'react';
import Link from 'next/link';
import Image from 'next/image';
import { Product } from '@/types';
import { useEloraStore } from '@/store/eloraStore';
import { Heart, ShoppingBag } from 'lucide-react';

interface ProductCardProps {
  product: Product;
}

export const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  const language = useEloraStore((state) => state.language);
  const addToCart = useEloraStore((state) => state.addToCart);
  const toggleWishlist = useEloraStore((state) => state.toggleWishlist);
  const isInWishlist = useEloraStore((state) => state.isInWishlist(product.id));

  const title = language === 'bn' ? product.name_bn : product.name_en;
  const brandName = product.brand_id || 'Maison Elora';

  // Discount percentage
  const discountPercent = product.compare_at_price && product.compare_at_price > product.price
    ? Math.round(((product.compare_at_price - product.price) / product.compare_at_price) * 100)
    : 0;

  const handleAddToCart = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    addToCart(product, 1);
  };

  const handleToggleWishlist = (e: React.MouseEvent) => {
    e.preventDefault();
    e.stopPropagation();
    toggleWishlist(product);
  };

  // Dedicated image fallback mapping to Image 02 cropped reference photos
  const getProductImage = () => {
    if (product.slug.includes('the-ordinary') || product.name_en.includes('Ordinary')) {
      return '/images/products/prod-the-ordinary-ref.jpg';
    }
    if (product.slug.includes('snail') || product.name_en.includes('COSRX')) {
      return '/images/products/prod-cosrx-ref.jpg';
    }
    if (product.slug.includes('laneige') || product.name_en.includes('Lip Sleeping')) {
      return '/images/products/prod-laneige-ref.jpg';
    }
    if (product.slug.includes('versace') || product.name_en.includes('Bright Crystal')) {
      return '/images/products/prod-versace-ref.jpg';
    }
    return product.images[0] || '/images/products/prod-the-ordinary-ref.jpg';
  };

  const imageSrc = getProductImage();

  return (
    <div className="group relative bg-white border border-[#F0DFDF] rounded-2xl p-2.5 sm:p-3 hover:shadow-card transition-all flex items-center gap-2.5 sm:gap-3.5 h-[115px] sm:h-[125px]">
      
      {/* Left: Product Image with "New" Badge */}
      <div className="relative w-[85px] sm:w-[95px] h-[95px] sm:h-[105px] flex-shrink-0 bg-white rounded-xl overflow-hidden flex items-center justify-center">
        {product.is_new_arrival && (
          <span className="absolute top-1 left-1 z-10 px-1.5 py-0.5 rounded-full bg-[#C45E70] text-white text-[9px] font-bold tracking-wider leading-none shadow-2xs">
            New
          </span>
        )}
        <Link href={`/product/${product.slug}`} className="w-full h-full relative block">
          <Image
            src={imageSrc}
            alt={title}
            fill
            className="object-contain p-1 group-hover:scale-105 transition-transform duration-300"
          />
        </Link>
      </div>

      {/* Right: Product Details & Controls */}
      <div className="flex-1 min-w-0 flex flex-col justify-between h-full py-0.5">
        
        {/* Top: Brand, Title, Wishlist */}
        <div>
          <div className="flex items-start justify-between gap-1">
            <span className="text-[10px] font-bold text-maison-charcoal uppercase tracking-wider block truncate">
              {brandName}
            </span>

            {/* Heart Wishlist Icon at Upper Right */}
            <button
              onClick={handleToggleWishlist}
              aria-label="Wishlist"
              className="text-[#9E9093] hover:text-[#C45E70] p-0.5 transition-colors focus:outline-none flex-shrink-0 cursor-pointer"
            >
              <Heart
                className={`w-3.5 h-3.5 stroke-[1.5] ${
                  isInWishlist ? 'fill-[#C45E70] text-[#C45E70]' : ''
                }`}
              />
            </button>
          </div>

          <Link href={`/product/${product.slug}`}>
            <h4 className="font-serif text-[11px] sm:text-[12px] font-medium text-maison-charcoal leading-snug line-clamp-1 hover:text-[#C45E70] transition-colors mt-0.5">
              {title}
            </h4>
          </Link>

          {product.volume_weight && (
            <span className="text-[10px] text-[#8C7F81] block mt-0.5">
              {product.volume_weight}
            </span>
          )}
        </div>

        {/* Bottom: Pricing & Cart Button */}
        <div className="flex items-end justify-between gap-1 mt-1">
          <div className="flex flex-wrap items-baseline gap-1">
            <span className="text-[12px] sm:text-[13px] font-bold text-[#C45E70]">
              ৳{product.price.toLocaleString()}
            </span>

            {product.compare_at_price && product.compare_at_price > product.price && (
              <>
                <span className="text-[10px] text-[#A8989A] line-through">
                  ৳{product.compare_at_price.toLocaleString()}
                </span>
                <span className="text-[9px] font-bold text-[#C45E70] bg-[#FDF5F6] px-1 py-0.2 rounded">
                  -{discountPercent}%
                </span>
              </>
            )}
          </div>

          {/* Small Rose Cart Button */}
          <button
            onClick={handleAddToCart}
            aria-label="Add to cart"
            className="w-6 h-6 sm:w-6.5 sm:h-6.5 rounded-lg bg-[#C45E70] hover:bg-[#B84A62] text-white flex items-center justify-center flex-shrink-0 transition-transform active:scale-90 shadow-2xs cursor-pointer"
          >
            <ShoppingBag className="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>
  );
};
