import LazyChatbot from "components/shop/lazy-chatbot";
import { ShopFooter } from "components/shop/shop-footer";
import { ShopNavbar } from "components/shop/shop-navbar";
import { getLandingPageData } from "lib/api";
import { AuthProvider } from "lib/hooks/useAuth";
import { CartProvider } from "lib/hooks/useCart";
import { ReactNode } from "react";

export const dynamic = "force-dynamic";
export const revalidate = 0;

export default async function ShopLayout({
  children,
}: {
  children: ReactNode;
}) {
  const data = await getLandingPageData();
  const settings = data?.siteSettings;

  return (
    <AuthProvider>
      <CartProvider>
        <div className="flex min-h-screen flex-col bg-slate-50">
          <header role="banner">
            <ShopNavbar />
          </header>
          <main className="flex-grow">{children}</main>
          <LazyChatbot />
          <ShopFooter settings={settings} />
        </div>
      </CartProvider>
    </AuthProvider>
  );
}
