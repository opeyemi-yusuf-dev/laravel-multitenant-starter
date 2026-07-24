# 🏢 Laravel Multi-Tenant Starter Architecture

A lightweight, scalable architectural pattern for building multi-tenant SaaS platforms in Laravel. Designed for isolated tenant database switching dynamically per HTTP request (ideal for EdTech ERPs, corporate CRMs, and white-label platforms).

---

## 🌟 Architectural Features

- **Dynamic Tenant Identification:** Intercepts incoming requests via custom headers (`X-Tenant-ID`) or subdomains to resolve tenant context.
- **On-the-Fly Database Isolation:** Automatically purges, reconfigures, and sets default database connections dynamically per request cycle without restarting services.
- **Isolated API Controllers:** Clean, isolated controller layers for serving tenant-scoped metrics and reporting safely.
- **Fail-Safe Routing:** Rejects unauthenticated or missing tenant requests before hit processing.

---

## 📁 Repository Code Structure

app/
├── Http/
│   ├── Controllers/
│   │   └── TenantReportController.php  # Handles tenant-scoped data & analytics
│   └── Middleware/
│       └── IdentifyTenant.php          # Resolves tenant & switches DB connection

---

## ⚙️ How It Works Under the Hood

### 1. Request Context Resolution (`IdentifyTenant.php`)
When an API request hits the server, the `IdentifyTenant` middleware inspects the request header (`X-Tenant-ID`) or domain string:

// Dynamic DB reconfiguration snippet
Config::set('database.connections.tenant.database', 'tenant_' . $tenantId);
DB::purge('tenant');
DB::reconnect('tenant');
DB::setDefaultConnection('tenant');

### 2. Tenant Execution Context (`TenantReportController.php`)
Once switched, all Eloquent queries and raw `DB` facade operations execute strictly within the active tenant's isolated database instance, avoiding cross-tenant data leaks.

---

## 🛠️ Requirements & Tech Stack

- **Language:** PHP 8.1+
- **Framework:** Laravel 10.x / 11.x
- **Database:** MySQL / PostgreSQL
- **Caching & Queues:** Redis
- **Protocol:** RESTful JSON APIs

---

## 🚀 Quick Setup & Installation

1. Clone the repository:
  git clone https://github.com/opeyemi-yusuf-dev/laravel-multitenant-starter.git
   cd laravel-multitenant-starter

2. Install dependencies:
   composer install

3. Configure Environment:
   cp .env.example .env
   php artisan key:generate

4. Testing Tenant Routing:
   Send an HTTP GET request with the tenant header:
   curl -H "X-Tenant-ID: school_a" http://localhost:8000/api/reports
