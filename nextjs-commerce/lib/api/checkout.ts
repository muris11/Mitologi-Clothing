import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import type { CheckoutPayload, CheckoutResponse } from "./types";

export async function createCheckout(
  payload: CheckoutPayload,
): Promise<CheckoutResponse> {
  return apiFetch<CheckoutResponse>(ENDPOINTS.CHECKOUT_PROCESS, {
    method: "POST",
    body: JSON.stringify(payload),
  });
}
