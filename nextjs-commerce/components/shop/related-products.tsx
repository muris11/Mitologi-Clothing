
import { ProductCard } from "components/shop/product-card";
import { getRelatedProducts } from "lib/api";

export async function RelatedProducts({ id }: { id: string }) {
  const relatedProducts = await getRelatedProducts(id);

  if (!relatedProducts.length) return null;

  return (
    <div className="py-12 border-t border-slate-200">
      <div className="flex items-center gap-4 mb-8">
        <div className="w-1.5 h-8 bg-mitologi-gold rounded-full" />
        <h2 className="text-2xl md:text-3xl font-sans font-bold text-mitologi-navy tracking-tight">
            Produk Terkait
        </h2>
      </div>
      
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 lg:gap-6">
        {relatedProducts.slice(0, 4).map((product, index) => (
          <ProductCard
            key={product.handle}
            product={product}
            index={index}
          />
        ))}
      </div>
    </div>
  );
}
