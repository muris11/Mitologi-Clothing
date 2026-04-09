import path from "node:path";
import { fileURLToPath } from "node:url";

const defaultApiUrl = "http://localhost:8011/api";
const publicApiUrl = process.env.NEXT_PUBLIC_API_URL || defaultApiUrl;
const internalApiUrl =
  process.env.INTERNAL_API_URL || publicApiUrl || defaultApiUrl;
const normalizedPublicApiUrl = publicApiUrl.includes("__HOST__")
  ? defaultApiUrl
  : publicApiUrl;
const projectRoot = path.dirname(fileURLToPath(import.meta.url));

const toBackendOrigin = (apiUrl: string) =>
  apiUrl.replace(/\/api\/?$/, "").replace(/\/+$/, "");

const backendPublicOrigin = toBackendOrigin(normalizedPublicApiUrl);
const backendInternalOrigin = toBackendOrigin(internalApiUrl);
const backendOrigins = Array.from(
  new Set([backendPublicOrigin, backendInternalOrigin]),
);

const toRemotePattern = (origin: string) => {
  try {
    const parsed = new URL(origin);
    return {
      protocol: parsed.protocol.replace(":", ""),
      hostname: parsed.hostname,
      port: parsed.port || undefined,
    };
  } catch {
    return null;
  }
};

const backendRemotePatterns = backendOrigins.map(toRemotePattern).filter(
  (
    pattern,
  ): pattern is {
    protocol: string;
    hostname: string;
    port: string | undefined;
  } => Boolean(pattern),
);

const isProduction = process.env.NODE_ENV === "production";
const siteUrl = process.env.NEXT_PUBLIC_SITE_URL || "https://mitologi.id";

export default {
  output: "standalone",
  allowedDevOrigins: ["localhost", "127.0.0.1", "192.168.2.101", "0.0.0.0"],
  turbopack: {
    root: projectRoot,
  },
  experimental: {
    inlineCss: false,
  },
  async rewrites() {
    return [
      {
        source: "/api/v1/:path*",
        destination: `${backendInternalOrigin}/api/v1/:path*`,
      },
      {
        source: "/storage/:path*",
        destination: `${backendInternalOrigin}/storage/:path*`,
      },
      {
        source: "/api/team-members/:path*",
        destination: `${backendInternalOrigin}/api/team-members/:path*`,
      },
    ];
  },
  images: {
    formats: ["image/avif", "image/webp"],
    unoptimized: !isProduction,
    dangerouslyAllowSVG: true,
    contentSecurityPolicy:
      "default-src 'self'; script-src 'none'; style-src 'none'; sandbox;",
    remotePatterns: [
      ...backendRemotePatterns,
      {
        protocol: "https",
        hostname: "placehold.co",
      },
      {
        protocol: "http",
        hostname: "localhost",
        port: "8011",
      },
      {
        protocol: "http",
        hostname: "127.0.0.1",
        port: "8011",
      },
      {
        protocol: "https",
        hostname: "images.unsplash.com",
      },
      {
        protocol: "https",
        hostname: "*.amazonaws.com",
      },
    ],
  },
  async headers() {
    const imgSources = [
      "'self'",
      "blob:",
      "data:",
      ...backendOrigins,
      "https://www.transparenttextures.com",
      "https://placehold.co",
      "https://images.unsplash.com",
      "https://snap-assets.al-pc-id-b.cdn.gtflabs.io",
    ];

    const connectSources = [
      "'self'",
      ...backendOrigins,
        "http://localhost:8011",
        "http://127.0.0.1:8011",
      "https://app.sandbox.midtrans.com",
      "https://app.midtrans.com",
      "https://api.sandbox.midtrans.com",
      "https://snap-assets.al-pc-id-b.cdn.gtflabs.io",
    ];

    if (!isProduction) {
      // Dev-only: allow dynamic LAN IP origins without editing CSP on every network change.
      imgSources.push("http:");
      connectSources.push("http:");
    }

    const cspDirectives = [
      `default-src 'self'`,
      `script-src 'self' ${!isProduction ? "'unsafe-eval'" : ""} 'unsafe-inline' https://app.sandbox.midtrans.com https://app.midtrans.com https://snap-assets.al-pc-id-b.cdn.gtflabs.io https://api.sandbox.midtrans.com https://pay.google.com`,
      `style-src 'self' 'unsafe-inline' https://fonts.googleapis.com`,
      `img-src ${imgSources.join(" ")}`,
      `font-src 'self' https://fonts.gstatic.com`,
      `connect-src ${connectSources.join(" ")}`,
      `frame-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com https://snap-assets.al-pc-id-b.cdn.gtflabs.io https://pay.google.com https://www.google.com https://maps.google.com`,
      `base-uri 'self'`,
      `form-action 'self'`,
      `frame-ancestors 'self'`,
    ];

    // Only upgrade insecure requests in production (breaks localhost HTTP)
    if (isProduction) {
      cspDirectives.push(`upgrade-insecure-requests`);
    }

    const headers = [
      {
        key: "Content-Security-Policy",
        value: cspDirectives.join("; "),
      },
      {
        key: "X-Content-Type-Options",
        value: "nosniff",
      },
      {
        key: "X-Frame-Options",
        value: "SAMEORIGIN",
      },
      {
        key: "X-XSS-Protection",
        value: "1; mode=block",
      },
      {
        key: "Referrer-Policy",
        value: "strict-origin-when-cross-origin",
      },
      {
        key: "Permissions-Policy",
        value: "camera=(), microphone=(), geolocation=(self), payment=(self)",
      },
    ];

    if (isProduction) {
      headers.push({
        key: "Strict-Transport-Security",
        value: "max-age=63072000; includeSubDomains; preload",
      });
    }

    headers.push({
      key: "Access-Control-Allow-Origin",
      value: isProduction ? siteUrl : "*",
    });

    return [
      {
        source: "/:path*",
        headers,
      },
    ];
  },
};
