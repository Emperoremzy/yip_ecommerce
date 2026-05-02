# YIP E-commerce

Basic e-commerce web application built with **Laravel 12**, **Bootstrap 5** (via CDN), and a small **service-oriented** layer over classic MVC. Customers can browse products, manage a session cart, check out with shipping details, and view **order history**. Admins can manage order status.

## Requirements

- PHP **8.2+**
- Composer
- A database supported by Laravel (MySQL, PostgreSQL, SQLite, etc.)

## Quick start

From the project directory:

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configure your database connection in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

Open `http://127.0.0.1:8000` (or your Laragon/virtual host URL). `/` redirects to the product catalogue.

### Demo accounts (`db:seed`)

| Role | Email | Notes |
|------|-------|------|
| Admin | `admin@example.com` | Access **Admin → Orders** at `/admin/orders` |
| Customer | `test@example.com` | Place orders and use **My orders** |

Password for seeded accounts is **`password`** (see `database/factories/UserFactory.php`).

---

## Features

- **Products** — Listing with pagination, category and price filters; detail page with stock and add-to-cart.
- **Cart** — Session-based add / update quantity / remove; running total.
- **Checkout** — Shipping form and order summary; **idempotent** order creation via UUID `idempotency_key`, DB unique constraint, and transactional stock handling.
- **Auth** — Register, login, logout; validated input; passwords hashed (`User` casts).
- **Customer order history** — `/orders`: list orders with status; link to `/orders/{id}` for details.
- **Admin** — Role flag `is_admin`; middleware `admin`; order list and status updates: Pending → Shipped → Delivered.

## API (REST-style)

Registered in `routes/api.php` (session `auth` for protected routes when called from same app context):

| Method | Path | Description |
|--------|------|--------------|
| `GET` | `/api/products` | Paginated/filtered products |
| `GET` | `/api/products/{product}` | Product detail |
| `GET` | `/api/orders/{order}` | Order detail (owner or admin only) |

## Architecture

- **Controllers** — Thin; HTTP concerns and validation stay here.
- **Services** (`app/Services/`) — `ProductService`, `CartService`, `AuthService`, `OrderService`, `AdminOrderService`.
- **Supporting** (`app/Support/`) — e.g. `ProductImage` for consistent product image URLs and fallbacks.
- **Security** — CSRF on web forms; Eloquent queries; Blade escaping; authenticated + admin middleware on sensitive routes.

## Useful routes (web)

| Path | Purpose |
|------|---------|
| `/products` | Catalogue |
| `/cart` | Cart |
| `/checkout` | Checkout (authenticated) |
| `/orders` | My orders (authenticated) |
| `/orders/{order}` | Order receipt / detail |
| `/admin/orders` | Admin order management |
| `/register`, `/login` | Authentication |

---

## Testing

```bash
php artisan test
```

---

## License

Application code inherits the Laravel ecosystem’s permissive conventions; Laravel itself is MIT-licensed — see Laravel’s documentation for upstream license terms.
