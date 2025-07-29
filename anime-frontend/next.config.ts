import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  webpack(config, { dev }) {
    if (dev) {
      config.watchOptions = {
        poll: 1000,
        aggregateTimeout: 300,
      };
    }
    return config;
  },
  images: {
    domains: ["storageanimesite.blob.core.windows.net", "upload.wikimedia.org"],
  },
};

export default nextConfig;
