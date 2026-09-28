# Discovery Executive Summary

**Project:** Hiii · **Generated:** 9/28/2026, 12:19:33 PM

> **Executive Summary**
>
> This report consolidates the overall ratings, key findings, and recommended actions from the 1 discovery analysis run across this codebase (frontend and backend). Each section below reproduces that analysis's executive view; full evidence and diagrams live in the individual reports.

## Portfolio Overview

| # | Analysis | Overall Rating |
|---|---|---|
| 1 | Architecture & Design Analysis | — |

---

## 1. Architecture & Design Analysis

> **Executive Summary**
>
> Invoice Ninja is a mature Laravel monolith that already invests in a Service Layer (328 service classes) and a Repository Layer (39 repositories), so the classic \"no service tier at all\" failure mode is largely avoided — the real risk lives elsewhere. The backend's dominant hotspots are **God Classes** (26 files exceed 1,000 LOC, led by `PdfBuilder`, `TemplateService`, `SubscriptionService`, and a 1,366-line `BaseController` that every API controller extends and that eager-loads ~30 distinct domain models) and **Shared Utility Abuse** (18 files under `app/Utils` and `app/Helpers` carry heavy business logic such as `HtmlEngine` at 1,285 LOC and the invoice-sum calculators). Controllers are moderately fat: 44 exceed 300 LOC and the service layer is bypassed by direct `Model::` access in 28 controllers, though direct raw SQL in controllers is effectively zero (excellent ORM discipline). The **React frontend is the sharpest architectural liability**: all 801 components live in a scaffolded `resources/js/enterprise/**` tree, average ~305 LOC each, embed `fetch('/api/v1/...')` calls and ~1,600 hard-coded API URLs inline (no shared data/service layer), and repeat 40 near-identical `helper1..40` calculation functions per file. The dominant risk is **change amplification** — a single API-shape or cross-cutting change ripples through the God classes, the shared utilities, and 800 copy-pasted frontend components simultaneously. Layers covered: **Backend** (2,442 PHP files in `app/`) and **Frontend** (801 React `.tsx` components); the client portal is a separate `.ts/.js` bundle (54 files) noted but not the primary focus.

### §1.1 Benchmark Ratings Summary

| # | Hotspot | Primary KPI | <span class=\"rating rating-good\">Good</span> | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"rating rating-high-risk\">High Risk</span> | Measured | Rating |
|---|---|---|---|---|---|---|---|
| H1 | Fat Controllers | Avg LOC per controller | <150 | 150–300 | >300 | 182 avg (raw); 44 controllers >300 LOC; max 1,366 (`BaseController`) | <span class=\"rating rating-moderate\">Moderate</span> |
| H2 | Missing Service Layer | Controllers accessing repos/models directly | <10 | 10–20 | >20 | 28 controllers use direct `Model::` access (service layer exists but bypassed) | <span class=\"rating rating-moderate\">Moderate</span> |
| H3 | Missing Repository Pattern | Direct DB access points outside repositories | <10 | 10–20 | >20 | 15 files with `DB::` outside repos; widespread `Model::` active-record use | <span class=\"rating rating-moderate\">Moderate</span> |
| H4 | Circular Dependencies | Dependency cycles | 0 | 1–3 | >3 | 0 detected via import scan (resolved through Laravel DI container) | <span class=\"rating rating-good\">Good</span> |
| H5 | Shared Utility Abuse | Utility/helper files holding business logic | 0 | 1–5 | >5 | 18 files in `app/Utils`+`app/Helpers` >300 LOC with business logic | <span class=\"rating rating-high-risk\">High Risk</span> |
| H6 | Direct SQL in Controllers | ORM compliance % | >90% | 60–90% | <60% | 100% — 0 `DB::` raw queries in any controller | <span class=\"rating rating-good\">Good</span> |
| H7 | God Classes | Classes >1000 LOC | 0 | 1–3 | >3 | 26 files >1,000 LOC (excl. 3 pure data-array providers) | <span class=\"rating rating-high-risk\">High Risk</span> |
| H8 | Domain Boundary Violations | Cross-domain access points | 0 | 1–5 | >5 | `BaseController` eager-loads ~30 domain models; no bounded contexts | <span class=\"rating rating-moderate\">Moderate</span> |
| H9 | Shared Database Coupling | Tables shared across domains | <10% | 10–30% | >30% | Single multi-tenant schema (~300 migrations), no per-domain ownership | <span class=\"rating rating-moderate\">Moderate</span> |
| F1 | Business Logic in Components | Avg LOC per component | <150 | 150–300 | >300 | 305 avg LOC across 801 components; `helper1..40` calc logic inline | <span class=\"rating rating-high-risk\">High Risk</span> |
| F2 | Missing Frontend Service/Data Layer | Components w/ inline API calls | <10 | 10–20 | >20 | 800 components with inline `fetch('/api/v1/...')`; ~1,600 hard-coded URLs | <span class=\"rating rating-high-risk\">High Risk</span> |
| F3 | God / Oversized Components | Components >400 LOC | 0 | 1–3 | >3 | 0 components exceed 400 LOC | <span class=\"rating rating-good\">Good</span> |
| F4 | Prop Drilling / Global State Abuse | Max prop-drilling depth | ≤2 | 3–4 | >4 | ≤1 — 0 `useContext`/`createContext`, self-contained pages | <span class=\"rating rating-good\">Good</span> |
| F5 | Legacy / Inconsistent Component Patterns | Legacy/inconsistent-pattern components | 0 | 1–10 | >10 | 0 class/deprecated components, but 0 error boundaries across 801 + copy-paste duplication | <span class=\"rating rating-moderate\">Moderate</span> |

No additional hotspots beyond the standard set were observed.

### §1.4 Actions Required

| Hotspot | Action | Rating | Priority |
|---|---|---|---|
| H7 God Classes | Decompose `PdfBuilder` (2,410), `TemplateService` (1,828), `SubscriptionService`, `BaseController` and 22 other >1,000-LOC files by responsibility (SRP) | <span class=\"rating rating-high-risk\">High Risk</span> | <span class=\"sev sev-critical\">Critical</span> |
| F2 Missing Frontend Service/Data Layer | Replace 1,600 inline `fetch('/api/v1/...')` calls with a shared typed API client + query-hook factory; remove mock fallbacks | <span class=\"rating rating-high-risk\">High Risk</span> | <span class=\"sev sev-critical\">Critical</span> |
| H5 Shared Utility Abuse | Extract `HtmlEngine`, `InvoiceSum`, `GeneratesCounter` and 15 other util/helper engines into owned domain services with interfaces | <span class=\"rating rating-high-risk\">High Risk</span> | <span class=\"sev sev-high\">High</span> |
| F1 Business Logic in Components | Extract the `helper1..40` scoring family and inline filtering into shared hooks/utilities; reduce pages to presentation | <span class=\"rating rating-high-risk\">High Risk</span> | <span class=\"sev sev-high\">High</span> |
| H1 Fat Controllers | Extract `bulk()`/`update()` orchestration (incl. the `usleep` polling loop) from `InvoiceController` & siblings into Application Services | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-high\">High</span> |
| H2 Missing Service Layer (bypassed) | Route the 28 controllers' direct `Model::` queries through the already-injected repositories/services; add a lint guard | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-medium\">Medium</span> |
| H3 Missing Repository Pattern (bypassed) | Move `DB::raw` reporting/chart SQL and `Invoice.php` queries behind a reporting repository/read-model | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-medium\">Medium</span> |
| H8 Domain Boundary Violations | Delegate `BaseController` include maps to per-domain transformers behind an `IncludeResolver`; define bounded contexts | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-medium\">Medium</span> |
| H9 Shared Database Coupling | Assign per-context table ownership; expose cross-context reads via internal APIs/DTOs (anti-corruption layer) | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-medium\">Medium</span> |
| F5 Legacy / Inconsistent Patterns | Add a shared `<ErrorBoundary>` and a reusable `ResourcePage` primitive; compose pages instead of cloning | <span class=\"rating rating-moderate\">Moderate</span> | <span class=\"sev sev-medium\">Medium</span> |

### §1.5 Expected Outcomes

- **Change amplification collapses:** a single API-shape, auth-header, or scoring-rule change is made once in a shared client/service instead of across 800 cloned components or 28 controllers.
- **Testability rises sharply:** decomposed `PdfBuilder`/`TemplateService` collaborators and an owned `InvoiceCalculator` can be unit-tested in isolation, protecting the product's most critical billing math.
- **Domains can evolve independently:** removing the all-domains `BaseController` coupling and introducing bounded contexts + anti-corruption layers lets Billing, Reporting, and Documents change (and eventually extract) without cross-breakage.
- **Frontend resilience improves:** a shared API client with global error handling and route-level error boundaries stops a single bad payload from blanking the UI and surfaces real failures instead of silent mock data.
- **Lower onboarding cost:** thin controllers, domain-owned services, and a reusable page primitive give new contributors clear seams to extend, replacing the \"clone a 300-line file\" workflow.","stop_reason":"end_turn","session_id":"1aba09c8-8832-4bf0-9978-483ea4f5b313","total_cost_usd":2.577165,"usage":{"input_tokens":13497,"cache_creation_input_tokens":81577,"cache_read_input_tokens":1575450,"output_tokens":36223,"server_tool_use":{"web_search_requests":0,"web_fetch_requests":0},"service_tier":"standard","cache_creation":{"ephemeral_1h_input_tokens":81577,"ephemeral_5m_input_tokens":0},"inference_geo":"not_available","iterations":[{"input_tokens":2,"output_tokens":3743,"cache_read_input_tokens":100741,"cache_creation_input_tokens":419,"cache_creation":{"ephemeral_5m_input_tokens":0,"ephemeral_1h_input_tokens":419},"type":"message"}],"speed":"standard"},"modelUsage":{"claude-haiku-4-5-20251001":{"inputTokens":545,"outputTokens":13,"cacheReadInputTokens":0,"cacheCreationInputTokens":0,"webSearchRequests":0,"costUSD":0.00061,"contextWindow":200000,"maxOutputTokens":32000},"claude-opus-4-8[1m]":{"inputTokens":13497,"outputTokens":36223,"cacheReadInputTokens":1575450,"cacheCreationInputTokens":81577,"webSearchRequests":0,"costUSD":2.576555,"contextWindow":1000000,"maxOutputTokens":64000}},"permission_denials":[],"terminal_reason":"completed","fast_mode_state":"off","uuid":"90602b5e-b140-41b5-8417-7fac04641f36"}