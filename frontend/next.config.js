const path = require("node:path")

/** @type {import('next').NextConfig} */
const nextConfig = {
    reactStrictMode: true,
    eslint: {
        ignoreDuringBuilds: false,
    },
    images: {
        remotePatterns: [
            {
                protocol: "https",
                hostname: "**",
            },
        ],
    },
}
if (process.env.NODE_ENV === "development") {
    nextConfig.outputFileTracingRoot = path.join(__dirname, "../../")
}

module.exports = nextConfig
