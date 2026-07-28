# Codebase Brief

**App:** Invoice Ninja **v5.13.24** (self-hosted invoicing), plus a **Klearcom IVR demo** UI on `/` and **80 enterprise modules** under `Modules/`.

## LOC (~5.06M total)

| Language | Files | Lines |
|----------|------:|------:|
| PHP | 7,784 | 4,204,861 |
| TS/TSX | 1,602 | 567,316 |
| JSON/YAML/SQL | 188 | 232,058 |
| Blade/HTML | 512 | 49,929 |
| JS/JSX/Vue | 65 | 8,912 |
| CSS/SCSS | 8 | 1,122 |
| **Total** | **10,159** | **5,064,198** |

Of that, ~**3.77M LOC** is the generated enterprise module expansion.

## Tech stack & versions

| Layer | Version |
|-------|---------|
| **PHP** | `>=8.3,<9.0` (runtime **8.3.32**) |
| **Laravel** | `^12.50` (installed **12.61.1**) |
| **React** | **19.2.3** |
| **React DOM** | **19.2.3** |
| **React Router** | **5.2.0** (legacy) |
| **TanStack React Query** | **5.90.20** |
| **TypeScript** | **5.6.3** |
| **Vite** | **7.3.1** |
| **Vitest** | **4.0.18** |
| **PHPStan** | `^1.12\|^2.0` (config **level 6**) |
| **PHPUnit** | `^11.0` |
| **DB** | MySQL/MariaDB (app default) |
| **Also present** | Livewire, Laravel Modules, Elasticsearch/Scout, Tailwind, Cypress |

## Demo navigation

- **Klearcom IVR demo:** `/`
- **Invoice Ninja UI:** `/app`
- **Setup:** `/setup`
