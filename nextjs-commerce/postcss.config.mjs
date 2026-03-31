import path from "node:path";
import { fileURLToPath } from "node:url";

const projectRoot = path.dirname(fileURLToPath(import.meta.url));

/** @type {import('postcss-load-config').Config} */
export default {
  plugins: {
    "@tailwindcss/postcss": {
      // Keep Tailwind resolution anchored to nextjs-commerce even if cwd is repo root.
      base: projectRoot,
    },
  },
};
