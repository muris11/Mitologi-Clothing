
export interface ApiErrorResponse {
  message: string;
  errors?: Record<string, string[]>;
}

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(
    message: string,
    status: number,
    errors?: Record<string, string[]>,
  ) {
    super(message);
    this.name = "ApiError";
    this.status = status;
    this.errors = errors;
  }

  getFieldError(field: string): string | undefined {
    return this.errors?.[field]?.[0];
  }

  isValidationError(): boolean {
    return this.status === 422;
  }

  isAuthError(): boolean {
    return this.status === 401;
  }

  isEmailTaken(): boolean {
    const emailErrors = this.errors?.["email"] || [];
    return emailErrors.some(
      (e) =>
        e.toLowerCase().includes("already been taken") ||
        e.toLowerCase().includes("sudah terdaftar") ||
        e.toLowerCase().includes("already exists"),
    );
  }
}

function trimTrailingSlash(url: string): string {
  return url.replace(/\/+$/, "");
}

function resolveDynamicHost(url: string): string {
  if (typeof window === "undefined") return url;
  return url.replace(/__HOST__/g, window.location.hostname);
}

/**
 * Resolve API base URL based on runtime:
 * - Browser/client components: NEXT_PUBLIC_API_URL
 * - Server runtime (RSC/SSR): INTERNAL_API_URL fallback to NEXT_PUBLIC_API_URL
 */
export function resolveApiBaseUrl(): string {
  const publicApiUrl = process.env.NEXT_PUBLIC_API_URL;
  const internalApiUrl = process.env.INTERNAL_API_URL;
  const isServerRuntime = typeof window === "undefined";

  const resolved = isServerRuntime
    ? (internalApiUrl || publicApiUrl)
    : publicApiUrl;

  if (!resolved) {
    throw new ApiError("NEXT_PUBLIC_API_URL belum dikonfigurasi.", 500);
  }

  return trimTrailingSlash(resolveDynamicHost(resolved));
}

/** Resolve backend origin from resolved API base URL (strip /api suffix). */
export function resolveBackendBaseUrl(): string {
  return resolveApiBaseUrl().replace(/\/api\/?$/, "");
}

/** Default server-side revalidation interval for GET requests (in seconds) */
const DEFAULT_REVALIDATION_SECONDS = 0; // Set to 0 for strict real-time data

/** Type-safe Next.js fetch config for caching and revalidation */
interface NextFetchConfig {
  tags?: string[];
  revalidate?: number;
}

/**
 * Recursively converts snake_case keys to camelCase.
 * This normalizes Laravel's snake_case API responses to JavaScript conventions,
 * eliminating the need for dual-key patterns like `data.order_id || data.orderId`.
 */
function normalizeKeys<T>(obj: unknown): T {
  if (Array.isArray(obj)) {
    return obj.map((item) => normalizeKeys(item)) as T;
  }
  if (obj !== null && typeof obj === 'object' && !(obj instanceof Date)) {
    const result: Record<string, unknown> = {};
    for (const [key, value] of Object.entries(obj as Record<string, unknown>)) {
      // Convert snake_case to camelCase
      const camelKey = key.replace(/_([a-z])/g, (_, c) => c.toUpperCase());
      // Keep both original and camelCase key so existing code isn't broken
      result[key] = normalizeKeys(value);
      if (camelKey !== key) {
        result[camelKey] = result[key];
      }
    }
    return result as T;
  }
  return obj as T;
}

/**
 * Centralized API fetch wrapper with built-in JSON handling,
 * CSRF token injection, ISR caching, and structured error handling.
 *
 * @param endpoint - API path or full URL
 * @param options  - Standard fetch options
 * @param tags     - Next.js cache tags for on-demand revalidation
 * @param revalidate - Custom revalidation interval (seconds)
 */
export async function apiFetch<T>(
  endpoint: string,
  options: RequestInit = {},
  tags?: string[],
  revalidate?: number,
): Promise<T> {
  const baseUrl = resolveApiBaseUrl();
  const url = endpoint.startsWith("http") ? endpoint : `${baseUrl}${endpoint}`;
  const method = (options.method || "GET").toUpperCase();
  const isReadRequest = method === "GET" || method === "HEAD";

  const headers: HeadersInit = {
    Accept: "application/json",
    ...options.headers,
  };

  // Ensure cookies are sent (important for cartSessionId and Sanctum)
  if (!options.credentials) {
    (options as RequestInit).credentials = "include";
  }

  if (!(options.body instanceof FormData)) {
    (headers as Record<string, string>)["Content-Type"] = "application/json";
  }

  if (typeof window !== "undefined") {
    const match = document.cookie.match(new RegExp('(^| )XSRF-TOKEN=([^;]+)'));
    if (match && match[2]) {
      (headers as Record<string, string>)["X-XSRF-TOKEN"] = decodeURIComponent(match[2]);
    }

    if (!(headers as Record<string, string>)["Authorization"]) {
      const matchAuth = document.cookie.match(new RegExp('(^| )auth_token=([^;]+)'));
      if (matchAuth && matchAuth[2]) {
        (headers as Record<string, string>)["Authorization"] = `Bearer ${decodeURIComponent(matchAuth[2])}`;
      }
    }
  }

  const nextConfig: NextFetchConfig = {};
  if (tags) nextConfig.tags = tags;

  // User explicitly wants everything to be 100% realtime instantly.
  // We'll set cache: "no-store", which forces Next.js to always fetch fresh data.
  const fetchCacheOption = options.cache || "no-store";

  try {
    const res = await fetch(url, {
      ...options,
      headers,
      next: Object.keys(nextConfig).length > 0 && fetchCacheOption !== "no-store" ? nextConfig : undefined,
      cache: fetchCacheOption, // Ensure no-store is passed to disable caching
    } as RequestInit);

    if (res.status === 204) {
      return {} as T;
    }

    if (!res.ok) {
      const errorBody = await res.text();

      // For 404, return undefined silently - callers handle "not found" gracefully
      if (res.status === 404) {
        return undefined as unknown as T;
      }

      // Try to parse as JSON for structured errors
      try {
        // Many Laravel errors return {"error": "Message"} instead of {"message": "Message"}
        const errorJson = JSON.parse(errorBody);
        const errorMessage = errorJson.message || errorJson.error || `Request failed with status ${res.status}`;
        
        throw new ApiError(
          errorMessage,
          res.status,
          errorJson.errors,
        );
      } catch (e) {
        // If parsing fails, throw generic error
        if (e instanceof ApiError) throw e;
        const errorSnippet = errorBody.slice(0, 300);
        throw new ApiError(`API error ${res.status}: ${errorSnippet}`, res.status);
      }
    }

    const contentType = res.headers.get("content-type") || "";
    if (!contentType.toLowerCase().includes("application/json")) {
      const responseText = (await res.text()).slice(0, 300);
      throw new ApiError(
        `Network error or invalid JSON response: Unexpected content-type "${contentType}" (${method} ${url}) ${responseText}`,
        500,
      );
    }

    try {
      const data = await res.json();
      return normalizeKeys<T>(data);
    } catch (jsonError) {
      throw new ApiError(
        `Network error or invalid JSON response: ${jsonError instanceof Error ? jsonError.message : String(jsonError)} (${method} ${url})`,
        500,
      );
    }
  } catch (error) {
    // Only log unexpected errors to the console, not expected API responses (like 401, 422, etc.)
    if (!(error instanceof ApiError)) {
      const message = error instanceof Error ? error.message : String(error);
      console.warn(`apiFetch warning [${method} ${url}]: ${message}`);
    } else if (error.status === 401 && url.includes('/profile') && method === 'GET') {
      // Ignore 401 on profile GET requests in console as they are handled by auth hooks
    } else if (error.status !== 401 && error.status !== 404 && error.status !== 422) {
      // Log other non-common errors for debugging
      console.warn(`apiFetch error [${method} ${url}]: ${error.message} (${error.status})`);
    }

    if (error instanceof ApiError) {
      throw error;
    }
    // Network errors are thrown as 500 ApiError, caller should log if needed.
    throw new ApiError(
      `Network error or invalid JSON response: ${error instanceof Error ? error.message : String(error)} (${method} ${url})`,
      500,
    );
  }
}

export function getCookie(name: string): string | undefined {
  if (typeof document === "undefined") return undefined;
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop()?.split(";").shift();
  return undefined;
}

export interface Pagination {
  total: number;
  per_page: number;
  current_page: number;
  last_page: number;
}

export * from "./account";
export * from "./cart";
export * from "./catalog";
export * from "./chatbot";
export * from "./checkout";
export * from "./content";
export * from "./endpoints";
export * from "./orders";
export * from "./reviews";
export * from "./types";
export * from "./wishlist";

