# RS Sales Content

Custom WordPress plugin that serves as the backend API for the [RS Sales App](https://github.com/gtm4y5kghx-byte/rs-sales-app) PWA. Manages sales assets and exposes them via REST endpoints for offline-first content syncing.

## Tech Stack

- PHP, WordPress REST API
- Advanced Custom Fields (ACF Pro)
- Custom post types and taxonomies

## What It Does

- Registers custom post types for sales assets (PDFs, images, videos) and sales pages
- Exposes two REST endpoints with API key authentication:
  - `/wp-json/rs-sales/v1/content-manifest` — asset manifest with checksums, file sizes, and metadata for sync
  - `/wp-json/rs-sales/v1/app-content` — editorial content (homepage hero, FAQs, sales page layouts)
- Generates versioned manifests with MD5 checksums for change detection
- Bypasses EWWW ExactDN CDN rewriting to preserve original URLs for offline caching
