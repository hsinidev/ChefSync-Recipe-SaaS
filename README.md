# ChefSync — Hyper-Scalable Multi-Tenant Recipe & Culinary Blog SaaS

[![Developer](https://img.shields.io/badge/Developer-Hsini%20Mohamed-green.svg)](https://hsini.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](LICENSE)
[![Live](https://img.shields.io/badge/Live-recipes.hsini.dev-blue.svg)](https://recipes.hsini.dev)

> **Live URL**: [https://recipes.hsini.dev](https://recipes.hsini.dev)
> **Repository**: [https://github.com/hsinidev/ChefSync-Recipe-SaaS](https://github.com/hsinidev/ChefSync-Recipe-SaaS)

---

## Description

ChefSync is a production-ready, hyper-scalable multi-tenant SaaS platform for culinary blogs and recipe management. Features hybrid database tenancy (per-tenant isolated DB for enterprise + shared DB with row-level scoping for standard tier), Schema.org/Recipe JSON-LD SEO, dynamic ingredient portion scaler, and media processing pipeline.

---

## Key Features

- Hybrid tenancy model: isolated dedicated DB per enterprise tenant, shared DB with Global Query Scopes for standard tier
- Dynamic tenant resolver with Redis Cache-Aside pool and AES-256-GCM encrypted connection config
- Schema.org/Recipe JSON-LD SEO integration via RecipeSchemaService with Redis-cached output
- Alpine.js powered real-time ingredient portion scaler with fraction formatting (e.g. 1.5 -> '1 1/2')
- Async media processing pipeline: WebP conversion, 3 responsive sizes (Thumbnail/Card/Hero) via GD/Imagick
- Multi-cluster Redis topology: sessions, application cache, and Laravel Horizon queue workers
- Unsplash API integration with exponential backoff resilience and local fallback vectors
- Postmark bulk newsletter dispatch with rate-limiting middleware (50 concurrent connections max)

---

## Tech Stack

- **Laravel 12 (PHP 8.4 strict types)**
- **MySQL 8.4 (InnoDB, compound indexing)**
- **Redis 7.2 (multi-cluster)**
- **TailwindCSS v4**
- **Alpine.js v3**
- **Livewire**
- **Laravel Horizon**
- **Postmark**

---

## Developer

| Field | Info |
|---|---|
| **Name** | Hsini Mohamed |
| **Website** | [https://hsini.dev](https://hsini.dev) |
| **Email** | [contact@hsini.dev](mailto:contact@hsini.dev) |
| **GitHub** | [https://github.com/hsinidev](https://github.com/hsinidev) |

---

## License

This project is licensed under the **MIT License**.
