import { ChevronRightIcon, ShoppingBagIcon } from "@heroicons/react/24/outline";
import { ProductCard } from "components/shop/product-card";
import { StaggerGrid, StaggerGridItem } from "components/ui/motion";
import { getRecommendations } from "lib/api/recommendations";
import { getUser } from "lib/api/server-auth";
import { Product } from "lib/api/types";
import Link from "next/link";

export async function RecommendationsSection() {
  const user = await getUser();
  let products: Product[] = [];

  if (user) {
    try {
      products = await getRecommendations();
    } catch (e) {
      // Silent fail
    }
  }

  // Fallback if no user or no personalized data
  if (products.length === 0) {
    return null;
  }

  return (
    <div className="mb-8 bg-white rounded-3xl shadow-sm border border-slate-100 p-6 lg:p-8">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div className="flex items-center gap-4">
          <div className="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 text-slate-500 shadow-sm">
            <ShoppingBagIcon className="w-5 h-5" />
          </div>
          <div>
            <h2 className="text-2xl md:text-3xl font-sans font-bold text-mitologi-navy mb-1 tracking-tight">
              Rekomendasi Untuk Anda
            </h2>
            <p className="text-sm font-sans text-slate-500 font-medium tracking-wide">
              Dipilih khusus berdasarkan selera Anda
            </p>
          </div>
        </div>

        <Link
          href="/shop"
          className="hidden sm:flex items-center gap-2 text-sm font-sans font-bold text-mitologi-navy hover:text-mitologi-gold transition-colors group px-4 py-2 rounded-full hover:bg-slate-50"
        >
          Lihat Semua
          <ChevronRightIcon className="h-4 w-4 group-hover:translate-x-1 transition-transform" />
        </Link>
      </div>

      {/* Products Carousel / Grid */}
      <div className="relative">
        {/* Mobile Horizontal Scroll */}
        <div className="flex overflow-x-auto pb-6 -mx-4 px-4 sm:hidden snap-x snap-mandatory scrollbar-hide">
          {products.slice(0, 4).map((product, index) => (
            <div
              key={product.id}
              className="min-w-[260px] max-w-[260px] flex-shrink-0 mr-4 snap-center"
            >
              <ProductCard product={product} index={index} />
            </div>
          ))}
          <div className="min-w-[140px] flex items-center justify-center snap-center">
            <Link
              href="/shop"
              className="flex flex-col items-center gap-3 text-slate-500 hover:text-mitologi-navy transition-colors group"
            >
              <div className="h-14 w-14 rounded-full bg-slate-50 border border-slate-200 group-hover:bg-mitologi-navy group-hover:border-mitologi-navy group-hover:shadow-md group-hover:shadow-mitologi-navy/20 flex items-center justify-center transition-all">
                <ChevronRightIcon className="h-6 w-6 group-hover:text-white" />
              </div>
              <span className="text-sm font-sans font-bold">Lihat Semua</span>
            </Link>
          </div>
        </div>

        {/* Desktop Grid */}
        <StaggerGrid className="hidden sm:grid grid-cols-2 lg:grid-cols-4 gap-6">
          {products.slice(0, 4).map((product, index) => (
            <ProductCard key={product.id} product={product} index={index} />
          ))}
        </StaggerGrid>
      </div>

      <div className="mt-8 text-center sm:hidden">
        <Link
          href="/shop"
          className="inline-flex items-center justify-center w-full py-3.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-sans font-bold text-sm hover:bg-slate-50 hover:text-mitologi-navy hover:border-mitologi-navy/30 transition-all shadow-sm"
        >
          Lihat Semua Produk
        </Link>
      </div>
    </div>
  );
}
