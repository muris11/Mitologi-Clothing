import { cookies } from 'next/headers';
import { ENDPOINTS } from './endpoints';
import { apiFetch } from './index';
import { Product } from './types';

/**
 * Gets personalized recommendations for the current user via the backend gateway.
 * The backend handles the auth validation and the proxy to the AI service.
 */
export async function getRecommendations(userId?: number): Promise<Product[]> {
  try {
    const token = (await cookies()).get('auth_token')?.value;
    if (!token) return [];

    const response = await apiFetch<{ recommendations: Product[] }>(ENDPOINTS.USER_RECOMMENDATIONS, {
      method: "GET",
      headers: {
        Authorization: `Bearer ${token}`
      },
      cache: "no-store",
    });
    return response.recommendations || [];
  } catch (error) {
    console.error('Error fetching recommendations from backend:', error);
    return [];
  }
}

/**
 * Gets related products for a specific product.
 */
export async function getProductRecommendations(productId: string | number): Promise<Product[]> {
  try {
    return await apiFetch<Product[]>(ENDPOINTS.PRODUCT_RECOMMENDATIONS(productId));
  } catch (error) {
    console.error('Error fetching product recommendations:', error);
    return [];
  }
}
