import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import { CheckoutResponse, Order } from "./types";

export async function getOrders(options?: RequestInit): Promise<Order[]> {
  try {
    const response = await apiFetch<{ orders: Order[] }>(ENDPOINTS.ORDERS, options || {}, ["orders"]);
    return response.orders || [];
  } catch (error) {
    console.error("Error fetching orders:", error);
    return [];
  }
}

export async function getOrderDetail(
  orderNumber: string
): Promise<Order | undefined> {
  try {
    return await apiFetch<Order>(ENDPOINTS.ORDER_DETAIL(orderNumber), {}, [
      `order-${orderNumber}`,
    ]);
  } catch (error) {
    console.error(`Error fetching order ${orderNumber}:`, error);
    return undefined;
  }
}

// Previously checkCheckout existed, but we implement confirmOrderPayment instead
export async function confirmOrderPayment(
    orderNumber: string
): Promise<{ success: boolean; order?: Order }> {
    try {
        const order = await apiFetch<Order>(ENDPOINTS.ORDER_CONFIRM_PAYMENT(orderNumber), {
            method: 'POST'
        });
        return { success: true, order };
    } catch (error) {
        console.error(`Error confirming payment for order ${orderNumber}:`, error);
         return { success: false };
    }
}

export async function requestOrderRefund(
    orderNumber: string,
    reason: string
): Promise<{ success: boolean; message?: string }> {
    try {
        const response = await apiFetch<{ message: string }>(ENDPOINTS.ORDER_REQUEST_REFUND(orderNumber), {
            method: 'POST',
            body: JSON.stringify({ reason })
        });
        return { success: true, message: response.message };
    } catch (error: unknown) {
        const err = error as Error;
        console.error(`Error requesting refund for order ${orderNumber}:`, error);
        return { success: false, message: err.message || 'Failed to request refund' };
    }
}

export async function payOrder(orderNumber: string): Promise<CheckoutResponse> {
    return await apiFetch(ENDPOINTS.ORDER_PAY(orderNumber), {
        method: "POST",
        cache: "no-store",
    });
}

