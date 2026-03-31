import { cookies } from "next/headers";
import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import { User } from "./types";

export async function getUser(): Promise<User | null> {
  const token = (await cookies()).get("auth_token")?.value;

  if (!token) return null;

  try {
    const response = await apiFetch<{ user: User }>(ENDPOINTS.AUTH_USER, {
      headers: {
        Authorization: `Bearer ${token}`,
      },
      cache: "no-store", // Ensure we don't cache user data
    });
    return response.user; // Extract user from wrapper
  } catch (error) {
    return null;
  }
}
