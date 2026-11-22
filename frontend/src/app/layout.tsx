import type { Metadata } from 'next';
import './globals.css';

export const metadata: Metadata = {
  metadataBase: new URL(process.env.NEXT_PUBLIC_SITE_URL || 'http://localhost:3000'),
  title: 'Maison Elora — Premium Beauty & Care | Imported Cosmetics, Skincare, Perfumes & Chocolates',
  description: 'Explore a curated collection of 100% authentic skincare, cosmetics, perfumes & chocolates imported from the UK, France, Korea, and Italy for Bangladesh with fast nationwide delivery.',
  icons: {
    icon: '/images/brand/maison-elora-logo.jpg',
    apple: '/images/brand/maison-elora-logo.jpg',
  },
  openGraph: {
    title: 'Maison Elora — Premium Beauty & Care',
    description: '100% Authentic Imported Skincare, Cosmetics, Perfumes & Chocolates in Bangladesh.',
    images: ['/images/brand/maison-elora-logo.jpg'],
    locale: 'en_BD',
    type: 'website',
  },
};

export default function RootLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <html lang="en">
      <head>
        <link rel="icon" href="/images/brand/maison-elora-logo.jpg" />
      </head>
      <body className="bg-[#FFF9F2] text-[#292426] antialiased min-h-screen">
        {children}
      </body>
    </html>
  );
}
