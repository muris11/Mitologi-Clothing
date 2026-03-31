import { ENDPOINTS } from "./endpoints";
import { apiFetch } from "./index";
import { User } from "./types";

export async function getProfile(): Promise<User | undefined> {
  try {
    return await apiFetch<User>(ENDPOINTS.PROFILE);
  } catch {
    return undefined;
  }
}

export async function updateProfile(
  data: Partial<User> & {
    address?: string;
    city?: string;
    province?: string;
    postal_code?: string;
  }
): Promise<User> {
  return await apiFetch<User>(ENDPOINTS.PROFILE, {
    method: "PUT",
    body: JSON.stringify(data),
  });
}

export async function updateAvatar(
  file: File
): Promise<{ message: string; avatar_url: string; avatar: string }> {
  const formData = new FormData();
  formData.append("avatar", file);
  return await apiFetch<{ message: string; avatar_url: string; avatar: string }>(ENDPOINTS.PROFILE_AVATAR, {
    method: "POST",
    body: formData,
  });
}

export async function updatePassword(data: {
  current_password: string;
  password: string;
  password_confirmation: string;
}): Promise<{ message: string }> {
  return await apiFetch<{ message: string }>(ENDPOINTS.PROFILE_PASSWORD, {
    method: "PUT",
    body: JSON.stringify(data),
  });
}
