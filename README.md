# ICDMS Platform

Integrated Community Development Management System for InnoTech Future Foundation.

ICDMS is a Laravel and Livewire platform for managing community development programs, projects, beneficiaries, regional command structures, donations, reports, public content, galleries, and participant registration.

## What It Includes

- Role-based portal for Super Admins, coordinators, volunteers, students, donors, and employer partners.
- Regional command hierarchy: zones, states, LGAs, coordinators, and project leaders.
- Searchable geographic hierarchy with bulk LGA import from CSV or text files.
- Programs, projects, communities, beneficiaries, field reports, KPIs, and monitoring dashboards.
- Applicant registration with Google reCAPTCHA, privacy and terms acceptance, profile picture upload, and role-specific document requirements.
- Admin applicant approval, role assignment, geographic scope assignment, and applicant inspection.
- Blog management with drafts, publishing, featured images, public posts, and homepage highlights.
- Gallery management for Events, Meetings, and Academy activities with multiple image uploads.
- Public homepage, registration portal, blog, gallery, custom pages, donation portal, and PWA assets.
- Paystack donation verification and bank-transfer receipt review.

## Technology

- PHP 8.2+
- Laravel 12
- Livewire 4
- SQLite or MySQL
- Vite 7 and Tailwind CSS 4
- Spatie Laravel Permission and Activitylog
- Google reCAPTCHA and Paystack

## Requirements

- PHP 8.2 or newer with the required Laravel extensions.
- Composer.
- Node.js and npm.
- SQLite or MySQL.
- A web server configured for this project layout. This installation keeps `index.php` at the project root rather than in a separate `public` directory.

## Installation

```bash
git clone https://github.com/theharryscope/icdms.git
cd icdms
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm run build
```

Configure the database, mail, CAPTCHA, and payment settings in `.env` before using the application.

The project is configured to output production assets to the root-level `build/` directory because the application serves `index.php` from the project root.

## Local Development

Start the application server:

```bash
php artisan serve
```

For frontend hot reload, run Vite in a separate terminal:

```bash
npm run dev
```

Or use the combined development command:

```bash
composer run dev
```

## Environment Configuration

At minimum, configure these values for a complete setup:

```dotenv
APP_NAME="InnoTech Future Foundation"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
# Or configure DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD for MySQL.

NOCAPTCHA_SITEKEY=
NOCAPTCHA_SECRET=

PAYSTACK_PUBLIC_KEY=
PAYSTACK_SECRET_KEY=
```

Never commit `.env` or expose private keys in source control.

## Admin Workflows

After signing in as a Super Admin:

- **Regional Command**: create zones and states, register individual LGAs, import many LGAs, search the hierarchy, and assign registered users as geographic leaders.
- **User Management**: review applicants, approve or reject accounts, edit users, assign roles, and set geographic scope.
- **Blog**: create and publish articles with optional featured images.
- **Gallery**: create albums under Events, Meetings, or Academy, upload multiple photos, and publish them publicly.
- **Pages**: create public pages such as About, Privacy Policy, and Terms of Service.
- **Site Settings**: manage branding, logo, favicon, contact details, social links, and role WhatsApp groups.

## Public Routes

- `/` — public homepage
- `/register-portal` — applicant registration
- `/login` — portal login
- `/blog` — published blog posts
- `/gallery` — published gallery albums
- `/page/{slug}` — published custom pages
- `/icdms/donate` — donation portal

## File Storage

Public branding, blog, gallery, and profile images are stored under `uploads/site`. Applicant documents and donation receipts use the public storage disk. Ensure the relevant directories are writable by the web server in production.

Uploaded files are intentionally ignored by Git. Files uploaded in one environment must be uploaded again or copied through the deployment process to another environment.

## Testing

```bash
php artisan test
```

The feature tests expect the test database to have all application migrations applied. Configure the test database and run migrations before testing in a fresh environment.

## Deployment Notes

1. Set `APP_ENV=production`, `APP_DEBUG=false`, and a real `APP_URL`.
2. Configure the production database and run `php artisan migrate --force`.
3. Run `composer install --no-dev --optimize-autoloader`.
4. Run `npm install` and `npm run build`, or deploy the generated root-level `build/` directory.
5. Configure the web server to route requests through the root `index.php`.
6. Make `storage`, `bootstrap/cache`, and upload directories writable as required.
7. Configure CAPTCHA, Paystack, mail, queue, and cache services.
8. Clear and rebuild Laravel caches after configuration changes:

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Repository

[https://github.com/theharryscope/icdms](https://github.com/theharryscope/icdms)

## License

This project is provided under the license specified in the repository metadata.
