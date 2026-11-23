# STORPIFY — HOSTINGER WEB HOSTING PRODUCTION DEPLOYMENT GUIDE

This guide provides complete instructions for deploying the **STORPIFY** digital commerce platform to **Hostinger Web Hosting** (Shared / Cloud / WordPress hosting) with an external **Supabase PostgreSQL** database.

---

## 1. Hosting Architecture Overview

- **Web Server**: Apache / LiteSpeed (Hostinger hPanel)
- **Document Root**: `public` folder
- **PHP Version**: 8.2 or 8.3
- **Database**: External Supabase PostgreSQL via Session Pooler (`aws-0-ap-southeast-1.pooler.supabase.com:5432`)
- **Storage**: External Supabase Storage (Public + Private buckets)
- **Node.js Runtime on Server**: **NOT REQUIRED** (Vite assets are pre-built to `public/build`)
- **Redis / Docker**: **NOT MANDATORY** (Uses Database/File drivers for sessions, cache, and queues)

---

## 2. Step 1: Configure PHP & Extensions in Hostinger hPanel

1. Log in to **Hostinger hPanel**.
2. Navigate to **Websites** → Select your domain → Click **Manage**.
3. In the search bar, type **PHP Configuration** (under *Advanced*).
4. Set **PHP Version** to **PHP 8.2** or **PHP 8.3** and click **Update**.
5. Switch to the **PHP Extensions** tab. Ensure the following extensions are enabled (checked):
   - `pdo_pgsql` *(CRITICAL for Supabase PostgreSQL)*
   - `pgsql`
   - `curl`
   - `openssl`
   - `mbstring`
   - `fileinfo`
   - `gd`
   - `zip`
   - `intl`
   - `bcmath`
6. Switch to the **PHP Options** tab and set production values:
   - `upload_max_filesize`: `64M` (or higher)
   - `post_max_size`: `64M`
   - `memory_limit`: `256M`
   - `max_execution_time`: `300`

---

## 3. Step 2: Configure Document Root to `/public`

By default, Hostinger web hosting serves files directly from `public_html`. Because Laravel uses the `public/` subdirectory as its web root for security:

### Method A: Hostinger hPanel Document Root Change (Recommended)
1. In hPanel, go to **Websites** → **Domains** / **Subdomains**.
2. Edit your domain configuration and set the **Custom folder** to `public_html/public`.

### Method B: Root `.htaccess` Redirection (If Custom Folder is Not Available)
Create an `.htaccess` file inside `public_html/` with the following content:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

## 4. Step 3: Deploy Application Files

1. On your local development machine, run the frontend build:
   ```bash
   npm run build
   ```
   *Note: This generates `public/build/assets/` and `public/build/manifest.json`. You do not need to install Node.js on Hostinger.*

2. Prepare your deployment package:
   - Upload all project files to Hostinger via **Git**, **File Manager (ZIP)**, or **FTP**.
   - **Do not** upload the local `/vendor` folder if running `composer install` on Hostinger, or upload a pre-installed `vendor` folder if SSH is disabled.
   - Do not upload `.env` (create `.env` fresh on the server).

---

## 5. Step 4: Configure Production `.env`

In Hostinger File Manager or SSH, copy `.env.example` to `.env` and fill in your production values:

```ini
APP_NAME=STORPIFY
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
APP_TIMEZONE=Asia/Dhaka
APP_LOCALE=bn
APP_FALLBACK_LOCALE=en

# External Supabase PostgreSQL Connection Pooler
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.[YOUR-PROJECT-REF]
DB_PASSWORD=[YOUR-SUPABASE-PASSWORD]
DB_SSLMODE=require

# External Supabase Storage & REST API
SUPABASE_URL=https://[YOUR-PROJECT-REF].supabase.co
SUPABASE_ANON_KEY=[YOUR-SUPABASE-ANON-KEY]
SUPABASE_SERVICE_ROLE_KEY=[YOUR-SUPABASE-SERVICE-ROLE-KEY]

SUPABASE_STORAGE_BUCKET_PUBLIC=storpify-public-assets
SUPABASE_STORAGE_BUCKET_PRODUCTS=storpify-product-downloads
SUPABASE_STORAGE_BUCKET_SUBSCRIPTIONS=storpify-subscription-library
SUPABASE_STORAGE_BUCKET_PAYMENT_PROOFS=storpify-payment-proofs

# Hostinger Webmail / SMTP Settings
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=support@yourdomain.com
MAIL_PASSWORD=[YOUR-EMAIL-PASSWORD]
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=support@yourdomain.com
MAIL_FROM_NAME=STORPIFY
```

---

## 6. Step 5: Run Production Artisan Commands

Via Hostinger SSH (Terminal):
```bash
# Generate application key
php artisan key:generate --force

# Create database tables & seed initial data to Supabase
php artisan migrate --force
php artisan db:seed --force

# Optimize performance for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run system diagnostic preflight check
php artisan storpify:preflight
```

---

## 7. Step 6: Configure Hostinger Cron Job

STORPIFY uses scheduled tasks for subscription expiry verification, newsletter delivery, and payment reminder notifications.

1. In hPanel, search for **Cron Jobs**.
2. Select **Custom** type.
3. Set schedule to **Every Minute** (`* * * * *`).
4. Enter the command (adjust path to match your Hostinger user directory):
   ```bash
   /usr/bin/php /home/u123456789/domains/yourdomain.com/public_html/artisan schedule:run >> /dev/null 2>&1
   ```
5. Click **Save**.

---

## 8. Step 7: Create Your Super Admin Account

For security, hardcoded default admin passwords have been completely removed. You can create your custom Super Admin in either of two ways:

### Option A: From Supabase Dashboard SQL Editor (Recommended)
1. Open your **Supabase Dashboard** ➜ **SQL Editor**.
2. Run the provided Super Admin creation SQL script (replace email and password with your desired values).
3. The script automatically hashes the password using native Bcrypt and grants full Super Admin RBAC privileges.

### Option B: Via Hostinger SSH / Terminal
```bash
php artisan storpify:create-admin
```
Follow the interactive prompt to set your admin name, email, and password.

---

## 9. Verification & Preflight Diagnostic Tool

Once deployed, visit:
`https://yourdomain.com/admin/settings/preflight`

This live interface runs a complete health audit of:
- PHP version and loaded extensions (`pdo_pgsql`, `curl`, `openssl`).
- Outbound PostgreSQL connection to Supabase.
- Outbound HTTPS API connectivity to Supabase Storage.
- File upload and memory limits.

If all indicators show green, your STORPIFY platform is 100% production-ready!
