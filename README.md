<div align="center">
# 🚀 Chefsync Recipe Saas
### *Modern, High-Performance Laravel 12 Solution & Developer Suite*

<p align="center">
  [![Architect](https://img.shields.io/badge/Architect-Hsini%20Mohamed-0055ff?style=for-the-badge&logo=github&logoColor=white)](https://hsini.dev)
  [![Portfolio](https://img.shields.io/badge/Portfolio-hsini.dev-00c853?style=for-the-badge&logo=google-chrome&logoColor=white)](https://hsini.dev)
  [![Language](https://img.shields.io/badge/Language-PHP-f59e0b?style=for-the-badge)](https://github.com/hsinidev)
  [![Framework](https://img.shields.io/badge/Framework-Laravel%2012-6366f1?style=for-the-badge)](https://github.com/hsinidev)
  [![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)
</p>

</div>

---
## 🌟 Executive Overview

**Chefsync Recipe Saas** is a production-grade **PHP** platform engineered for high reliability, clean architectural separation, and frictionless developer workflow.

## ⚡ Key Highlights & Capabilities

- **Scalable Architecture**: Modular, decoupled components adhering to clean code principles.
- **Optimized Runtime**: Ultra-fast execution with minimal memory and CPU overhead.
- **Developer Tooling**: Standardized linting, formatting, and rapid local iteration setup.
- **Production Ready**: Built-in error resilience, validation, and structured logging.

---
## 🏗️ Architecture & Technology Stack

- **Primary Language**: `PHP`
- **Framework / Runtime**: `Laravel 12`
- **Design Pattern**: Modular Clean Architecture / Domain-Driven Design
- **License**: MIT Open Source Attribution

## 📖 Deep-Dive Technical Documentation

# ChefSync — Hyper-Scalable Multi-Tenant Recipe & Culinary Blog SaaS


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


| Field | Info |
|---|---|
| **Name** | Hsini Mohamed |
| **Website** | [https://hsini.dev](https://hsini.dev) |
| **Email** | [contact@hsini.dev](mailto:contact@hsini.dev) |
| **GitHub** | [https://github.com/hsinidev](https://github.com/hsinidev) |

---

---
## 🚀 Quick Start & Installation

### 1. Clone the Repository
```bash
git clone https://github.com/hsinidev/ChefSync-Recipe-SaaS.git
cd ChefSync-Recipe-SaaS
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Launch the Application
```bash
php artisan serve
```


---

## 👨‍💻 System Architect & Author

<table align="center" style="border: none; background: transparent; width: 100%;">
  <tr>
    <td align="center" width="160" style="border: none; padding: 12px;">
      <img src="https://avatars.githubusercontent.com/u/232697467?v=4" width="120" height="120" style="border-radius: 50%; box-shadow: 0 8px 24px rgba(99,102,241,0.3); border: 2.5px solid #6366f1;" alt="Hsini Mohamed" />
      <br /><br />
      <b>Hsini Mohamed</b><br />
      <sub>Morocco 🇲🇦</sub>
    </td>
    <td style="border: none; padding: 12px; vertical-align: middle;">
      <h3 style="margin-top: 0;">🚀 System Architect & Full-Stack Engineer</h3>
      <p style="font-size: 0.95rem; line-height: 1.6; color: #475569;">
        Specializing in high-performance autonomous AI systems, deterministic multi-agent swarms, enterprise cloud architecture, and modern full-stack engineering.
      </p>
      <p>
        <a href="https://hsini.dev"><img src="https://img.shields.io/badge/Portfolio-hsini.dev-2563eb?style=flat-square&logo=google-chrome&logoColor=white" alt="Portfolio" /></a>
        <a href="mailto:contact@hsini.dev"><img src="https://img.shields.io/badge/Email-contact@hsini.dev-ea4335?style=flat-square&logo=gmail&logoColor=white" alt="Email" /></a>
        <a href="https://github.com/hsinidev"><img src="https://img.shields.io/badge/GitHub-@hsinidev-181717?style=flat-square&logo=github&logoColor=white" alt="GitHub" /></a>
        <a href="https://linkedin.com/in/hsinidev/"><img src="https://img.shields.io/badge/LinkedIn-hsinidev-0077b5?style=flat-square&logo=linkedin&logoColor=white" alt="LinkedIn" /></a>
      </p>
    </td>
  </tr>
</table>

---

## 📄 License & Attribution

This project is distributed under the **MIT License**. See [`LICENSE`](LICENSE) for complete terms.

<div align="center">
  <sub>⚡ Designed, architected, and maintained with engineering precision by <b><a href="https://hsini.dev">Hsini Mohamed</a></b>.</sub>
</div>
