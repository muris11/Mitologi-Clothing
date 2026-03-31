import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import { LandingPageData, Menu, Page, PortfolioItem, SiteSettings } from "./types";

export async function getMenu(handle: string): Promise<Menu[]> {
  try {
    const data = await apiFetch<{ menu: Menu[] }>(
      ENDPOINTS.MENU(handle),
      {},
      [`menu-${handle}`]
    );
    return data.menu || [];
  } catch (error) {
    console.error(`Error fetching menu ${handle}:`, error);
    return [];
  }
}

export async function getPages(requestOptions?: RequestInit): Promise<Page[]> {
  try {
    return await apiFetch<Page[]>(ENDPOINTS.PAGES, requestOptions || {}, ["pages"]);
  } catch (error) {
    const message = error instanceof Error ? error.message : String(error);
    console.warn(`Error fetching pages (fallback to empty list): ${message}`);
    return [];
  }
}

export async function getPage(handle: string): Promise<Page | undefined> {
  try {
    return await apiFetch<Page>(ENDPOINTS.PAGE_DETAIL(handle), {}, [
      `page-${handle}`,
    ]);
  } catch (error) {
    console.error(`Error fetching page ${handle}:`, error);
    return undefined;
  }
}

export async function getLandingPageData(): Promise<LandingPageData> {
  try {
    return await apiFetch<LandingPageData>(ENDPOINTS.LANDING_PAGE, {}, [
      "landing-page",
    ]);
  } catch (error) {
    console.warn("Could not fetch landing page data, using fallback");
    return {
      hero: { title: "", subtitle: "", imageDesktop: "", imageMobile: "", tryOnImage: "", tryOnUrl: "" },
      hero_slides: [],
      features: [],
      orderFlow: [],
      order_steps: [],
      categories: [],
      portfolio_items: [],
      testimonials: [],
      partners: [],
      materials: [],
      new_arrivals: [],
      best_sellers: [],
      product_pricings: [],
      printing_methods: [],
      facilities: [],
      team_members: [],
      cta: undefined,
      site_settings: undefined,
    } as unknown as LandingPageData;
  }
}

export async function getPortfolios(): Promise<PortfolioItem[]> {
  try {
    return await apiFetch<PortfolioItem[]>(ENDPOINTS.PORTFOLIOS, {}, ["portfolios"]);
  } catch (error) {
    console.error("Error fetching portfolios:", error);
    return [];
  }
}

export async function getPortfolio(slug: string): Promise<PortfolioItem | undefined> {
  try {
    const data = await apiFetch<PortfolioItem>(
      ENDPOINTS.PORTFOLIO_DETAIL(slug),
      {},
      [`portfolio-${slug}`]
    );
    return data;
  } catch (error) {
    console.error(`Error fetching portfolio ${slug}:`, error);
    return undefined;
  }
}

export async function getSiteSettings(): Promise<SiteSettings | undefined> {
  try {
    const data = await apiFetch<{ data: SiteSettings }>(
      ENDPOINTS.SITE_SETTINGS,
      {},
      ["site-settings"]
    );
    return data.data;
  } catch (error) {
    console.warn("Could not fetch site settings, using fallback");
    return undefined;
  }
}
