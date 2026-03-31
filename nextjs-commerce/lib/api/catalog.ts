import { ENDPOINTS } from "./endpoints";
import { apiFetch, Pagination } from "./index";
import { Category, Collection, Material, OrderStep, Product } from "./types";

export async function getProducts({
  query,
  reverse,
  sortKey,
  category,
  minPrice,
  maxPrice,
  page,
  limit,
}: {
  query?: string;
  reverse?: boolean;
  sortKey?: string;
  category?: string;
  minPrice?: number;
  maxPrice?: number;
  page?: number;
  limit?: number;
}, requestOptions?: RequestInit): Promise<{ products: Product[]; pagination: Pagination }> {
  try {
    const searchParams = new URLSearchParams();

    if (query) searchParams.append("q", query);
    if (sortKey) searchParams.append("sortKey", sortKey);
    if (reverse) searchParams.append("reverse", "true");
    if (category) searchParams.append("category", category);
    if (minPrice !== undefined)
      searchParams.append("minPrice", minPrice.toString());
    if (maxPrice !== undefined)
      searchParams.append("maxPrice", maxPrice.toString());
    if (page !== undefined) searchParams.append("page", page.toString());
    if (limit !== undefined) searchParams.append("limit", limit.toString());

    return await apiFetch<{ products: Product[]; pagination: Pagination }>(
      `${ENDPOINTS.PRODUCTS}?${searchParams.toString()}`,
      requestOptions || {},
      ["products"]
    );
  } catch (error) {
    const message = error instanceof Error ? error.message : String(error);
    console.warn(`Error fetching products (fallback to empty list): ${message}`);
    return {
      products: [],
      pagination: { total: 0, per_page: 0, current_page: 1, last_page: 1 },
    };
  }
}

export async function getProduct(handle: string): Promise<Product | undefined> {
  try {
    const data = await apiFetch<Product | { product: Product }>(
      ENDPOINTS.PRODUCT_DETAIL(handle),
      {},
      [`product-${handle}`]
    );
    // Handle both wrapped `{ product: ... }` and flat `Product` shapes
    // Guard against undefined (e.g., 404 returns undefined from apiFetch)
    if (!data || typeof data !== 'object') return undefined;
    return 'product' in data ? data.product : data;
  } catch (error) {
    console.error(`Error fetching product ${handle}:`, error);
    return undefined;
  }
}

export async function getCollections(requestOptions?: RequestInit): Promise<Collection[]> {
  try {
    return await apiFetch<Collection[]>(ENDPOINTS.COLLECTIONS, requestOptions || {}, ["collections"]);
  } catch (error) {
    const message = error instanceof Error ? error.message : String(error);
    console.warn(`Error fetching collections (fallback to empty list): ${message}`);
    return [];
  }
}

export async function getCollection(
  handle: string
): Promise<Collection | undefined> {
  try {
    const data = await apiFetch<Collection>(
      ENDPOINTS.COLLECTION_DETAIL(handle),
      {},
      [`collection-${handle}`]
    );
    return data;
  } catch (error) {
    console.error(`Error fetching collection ${handle}:`, error);
    return undefined;
  }
}

export async function getCollectionProducts({
  collection,
  reverse,
  sortKey,
}: {
  collection: string;
  reverse?: boolean;
  sortKey?: string;
}): Promise<Product[]> {
  try {
    const searchParams = new URLSearchParams();
    if (sortKey) searchParams.append("sort", sortKey);
    if (reverse) searchParams.append("order", "desc");

    return await apiFetch<Product[]>(
      `${ENDPOINTS.COLLECTION_PRODUCTS(collection)}?${searchParams.toString()}`,
      {},
      [`collection-products-${collection}`]
    );
  } catch (error) {
    console.error(`Error fetching products for collection ${collection}:`, error);
    return [];
  }
}

export async function getCategories(): Promise<Category[]> {
  try {
    return await apiFetch<Category[]>(ENDPOINTS.CATEGORIES, {}, ["categories"]);
  } catch (error) {
    console.error("Error fetching categories:", error);
    return [];
  }
}

export async function getCategory(
  handle: string
): Promise<Category | undefined> {
  try {
    const data = await apiFetch<{ category: Category }>(
      ENDPOINTS.CATEGORY_DETAIL(handle),
      {},
      [`category-${handle}`]
    );
    return data.category;
  } catch (error) {
    console.error(`Error fetching category ${handle}:`, error);
    return undefined;
  }
}

export async function getBestSellers(limit?: number): Promise<Product[]> {
  try {
    const url = limit
      ? `${ENDPOINTS.PRODUCTS_BEST_SELLERS}?limit=${limit}`
      : ENDPOINTS.PRODUCTS_BEST_SELLERS;
    return await apiFetch<Product[]>(url, {}, ["best-sellers", "products"]);
  } catch (error) {
    console.error("Error fetching best sellers:", error);
    return [];
  }
}

export async function getNewArrivals(limit?: number): Promise<Product[]> {
  try {
    const url = limit
      ? `${ENDPOINTS.PRODUCTS_NEW_ARRIVALS}?limit=${limit}`
      : ENDPOINTS.PRODUCTS_NEW_ARRIVALS;
    return await apiFetch<Product[]>(url, {}, ["new-arrivals", "products"]);
  } catch (error) {
    console.error("Error fetching new arrivals:", error);
    return [];
  }
}

export async function searchProducts(query: string): Promise<Product[]> {
  try {
    const { products } = await getProducts({ query, limit: 10 });
    return products;
  } catch (error) {
    return [];
  }
}

export async function getRelatedProducts(productId: string): Promise<Product[]> {
  try {
    // Use recommendation endpoint first
    const recommended = await apiFetch<Product[]>(
      ENDPOINTS.PRODUCT_RECOMMENDATIONS(productId),
      {},
      [`recommendations-${productId}`]
    );
    if (recommended && recommended.length > 0) {
      return recommended.slice(0, 4);
    }
  } catch {
    // Fallback to random products if recommendation API fails
  }

  try {
    const { products } = await getProducts({ limit: 4 });
    return products.filter((p) => String(p.id) !== String(productId));
  } catch {
    return [];
  }
}


export async function getOrderSteps(): Promise<OrderStep[]> {
  try {
    return await apiFetch<OrderStep[]>(ENDPOINTS.ORDER_STEPS, {}, ["order-steps"]);
  } catch (error) {
    console.error("Error fetching order steps:", error);
    return [];
  }
}

export async function getMaterials(): Promise<Material[]> {
  try {
    return await apiFetch<Material[]>(ENDPOINTS.MATERIALS, {}, ["materials"]);
  } catch (error) {
    console.error("Error fetching materials:", error);
    return [];
  }
}
