import { AboutServicesDetail as ServicesDetail } from 'components/landing/about/services-detail';
import { CategoryPricelist } from 'components/landing/sections/category-pricelist';
import { PrintingMethods } from 'components/landing/sections/printing-methods';
import { SubpageHero } from "components/landing/shared/subpage-hero";
import { getLandingPageData } from 'lib/api';
import type { Metadata } from 'next';

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function generateMetadata(): Promise<Metadata> {
  const data = await getLandingPageData();
  const siteName = data.site_settings?.general?.site_name || 'Mitologi Clothing';
  const title = `Layanan Produksi — ${siteName}`;
  const description = `Layanan produksi dari ${siteName}: kemeja, jersey, sablon kaos, dan lainnya.`;

  return {
    title,
    description,
    openGraph: {
      title,
      description,
      type: 'website',
      url: `${process.env.NEXT_PUBLIC_SITE_URL || 'https://mitologi.id'}/layanan`,
    },
    twitter: {
      card: 'summary_large_image',
      title,
      description,
    }
  };
}

export default async function LayananPage() {
  const data = await getLandingPageData();

  return (
    <main>
      {/* Hero Section */}
      <SubpageHero
        title="Layanan Produksi"
        subtitle={data.site_settings?.general?.site_tagline || data.site_settings?.general?.site_description || 'Solusi lengkap untuk kebutuhan produksi pakaian Anda dengan kualitas terbaik.'}
        badge={true}
        badgeText="Program Kerja"
      />

      {/* Services Detail Section */}
      <ServicesDetail settings={data.site_settings} />

      {/* Product Pricelist */}
      <CategoryPricelist pricings={data.product_pricings} />

      {/* Printing Methods */}
      <PrintingMethods methods={data.printing_methods} />
    </main>
  );
}
