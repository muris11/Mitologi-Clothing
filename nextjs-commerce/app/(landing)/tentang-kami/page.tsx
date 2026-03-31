import { CompanyLegality } from "components/landing/about/company-legality";
import { FounderStory } from "components/landing/about/founder-story";
import { AboutHistory } from "components/landing/about/history-section";
import { ProductionFacilities } from "components/landing/about/production-facilities";
import { TeamStructure } from "components/landing/about/team-structure";
import { AboutVisionMission } from "components/landing/about/vision-mission";
import { SubpageHero } from "components/landing/shared/subpage-hero";
import { getLandingPageData } from "lib/api";

export const dynamic = 'force-dynamic';
export const revalidate = 0;

export async function generateMetadata() {
  const data = await getLandingPageData();
  const settings = data.site_settings;
  
  const title = `Tentang Kami | ${settings?.general?.site_name || 'Mitologi Clothing'}`;
  const description = settings?.general?.site_description || 'Profil Mitologi Clothing - Vendor Clothing profesional dari Indramayu yang mengutamakan kualitas, ketepatan waktu, dan nilai budaya.';

  return {
    title,
    description,
    openGraph: {
      title,
      description,
      type: 'website',
      url: `${process.env.NEXT_PUBLIC_SITE_URL}/tentang-kami`,
    },
    twitter: {
      card: 'summary_large_image',
      title,
      description,
    }
  };
}

export default async function AboutPage() {
  const data = await getLandingPageData();
  const settings = data.site_settings;

  return (
    <>
      <SubpageHero
        title={`Tentang ${settings?.general?.site_name || 'Kami'}`}
        subtitle={settings?.about?.about_headline || settings?.general?.site_tagline || settings?.general?.site_description || ''}
        badge={true}
        badgeText={settings?.general?.site_name || "Mitologi Clothing"}
      />
      <AboutHistory settings={settings} />
      <FounderStory settings={settings} />
      <AboutVisionMission settings={settings} />
      <ProductionFacilities facilities={data.facilities} />
      <CompanyLegality settings={settings} />
      <TeamStructure teamMembers={data.team_members} />
    </>
  );
}
