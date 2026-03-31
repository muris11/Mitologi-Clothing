import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import { Product } from "./types";

export async function getWishlist(): Promise<Product[]> {
  try {
    return await apiFetch<Product[]>(ENDPOINTS.WISHLIST, {}, ["wishlist"]);
  } catch (error) {
    console.error("Error fetching wishlist:", error);
    return [];
  }
}

export async function addToWishlist(productId: string | number): Promise<{ success: boolean; message: string }> {
  try {
    const res = await apiFetch<{ message: string }>(
      ENDPOINTS.WISHLIST_ADD(productId),
      {
        method: "POST",
      }
    );
    return { success: true, message: res.message };
  } catch (error) {
    console.error("Error adding to wishlist:", error);
    return { success: false, message: "Failed to add to wishlist" };
  }
}

export async function removeFromWishlist(productId: string | number): Promise<{ success: boolean; message: string }> {
  try {
    const res = await apiFetch<{ message: string }>(
      ENDPOINTS.WISHLIST_REMOVE(productId),
      {
        method: "DELETE",
      }
    );
    return { success: true, message: res.message };
  } catch (error) {
    console.error("Error removing from wishlist:", error);
    return { success: false, message: "Failed to remove from wishlist" };
  }
}

export async function checkWishlist(productId: string | number): Promise<boolean> {
  try {
    const res = await apiFetch<{ in_wishlist: boolean }>(
      ENDPOINTS.WISHLIST_CHECK(productId),
      {},
      [`wishlist-check-${productId}`]
    );
    return res.in_wishlist;
  } catch (error) {
    console.error("Error checking wishlist status:", error);
    return false;
  }
}
