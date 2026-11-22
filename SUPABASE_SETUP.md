# STORPIFY — SUPABASE POSTGRESQL & STORAGE INTEGRATION GUIDE

This document provides the exact step-by-step instructions for provisioning, configuring, and connecting your **Supabase** PostgreSQL database and Supabase Storage buckets to **STORPIFY** on Hostinger Web Hosting.

---

## 1. Supabase Project Creation

1. Log in to [Supabase Dashboard](https://supabase.com/dashboard).
2. Click **New Project**.
3. Choose your organization, set the **Name** to `STORPIFY`, and choose the region closest to your primary market (e.g., `Singapore (ap-southeast-1)` or `Mumbai (ap-south-1)` for lowest latency from Bangladesh).
4. Generate a strong Database Password and save it securely in your password manager.

---

## 2. Database Connection Architecture (Session Pooler)

Because Hostinger Web Hosting operates in an IPv4-only shared environment and requires high-concurrency handling:

> **IMPORTANT**: Use the **Supabase Session Pooler** on port `5432` with `sslmode=require`. **Do not** attempt to connect using the IPv6 Direct Connection on Hostinger.

### Connection String Extraction:
1. In Supabase Dashboard, go to **Project Settings** → **Database**.
2. Scroll to **Connection string** and select the **URI** tab.
3. Switch connection mode from *Direct Connection* to **Session Pooler** (Port `5432`).
4. The URI format will look like:
   ```
   postgresql://postgres.[PROJECT-REF]:[YOUR-PASSWORD]@aws-0-ap-southeast-1.pooler.supabase.com:5432/postgres?sslmode=require
   ```

### Updating your `.env` on Hostinger:
```ini
DB_CONNECTION=pgsql
DB_HOST=aws-0-ap-southeast-1.pooler.supabase.com
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres.[PROJECT-REF]
DB_PASSWORD=[YOUR-SUPABASE-DB-PASSWORD]
DB_SSLMODE=require

# Or use DB_URL directly:
DB_URL=postgresql://postgres.[PROJECT-REF]:[YOUR-PASSWORD]@aws-0-ap-southeast-1.pooler.supabase.com:5432/postgres?sslmode=require
```

---

## 3. Database Schema Deployment

You have two simple options to create the database tables:

### Option A: Direct SQL Import via Supabase Dashboard (Recommended for Shared Hosting)
1. In your Supabase Dashboard, open **SQL Editor**.
2. Click **New Query**.
3. Copy the full contents of `database/supabase_schema.sql` from your STORPIFY project repository.
4. Click **Run**.
5. All normalized relational tables (`users`, `products`, `orders`, `payments`, `subscription_plans`, etc.), indexes, and foreign keys will be created instantly.

### Option B: Laravel Artisan Migrations
If you have SSH access enabled in Hostinger:
```bash
php artisan migrate --force
php artisan db:seed --force
```

---

## 4. Supabase Storage Buckets Configuration

STORPIFY requires 4 dedicated storage buckets for secure digital asset storage and signed token downloads:

| Bucket Name | Privacy Level | Purpose |
| :--- | :--- | :--- |
| `storpify-public-assets` | **Public** | Product thumbnails, mockups, website banners, category icons |
| `storpify-product-downloads` | **Private** | Protected ZIP files, licenses, script source code for paid orders |
| `storpify-subscription-library` | **Private** | Protected subscription resources and files for active VIP members |
| `storpify-payment-proofs` | **Private** | Customer uploaded bKash/Nagad transaction screenshot proofs |

### Creating the Buckets in Supabase:
1. In Supabase Dashboard, navigate to **Storage**.
2. Click **New Bucket**:
   - Bucket 1: Name `storpify-public-assets`, Toggle **Public bucket** to `ON`.
   - Bucket 2: Name `storpify-product-downloads`, Toggle **Public bucket** to `OFF` (Private).
   - Bucket 3: Name `storpify-subscription-library`, Toggle **Public bucket** to `OFF` (Private).
   - Bucket 4: Name `storpify-payment-proofs`, Toggle **Public bucket** to `OFF` (Private).

---

## 5. Row-Level Security (RLS) & Signed Download Delivery

STORPIFY uses **server-side signed URLs** for maximum cybersecurity:
- Digital product downloads are generated with short-lived (60-minute) cryptographic signatures via `App\Services\SupabaseService`.
- No direct public download link is ever exposed to customers or indexed by Google.
- If a customer shares the download URL, it automatically expires after the configured token window.

### Bucket RLS Policies:
Run the following SQL in Supabase SQL Editor if you wish to enforce strict RLS on storage objects:

```sql
-- Allow public read only for public assets bucket
CREATE POLICY "Public Read Access for Public Assets"
ON storage.objects FOR SELECT
USING (bucket_id = 'storpify-public-assets');

-- Allow service role full access to all private buckets
CREATE POLICY "Service Role Full Access"
ON storage.objects FOR ALL
TO service_role
USING (true)
WITH CHECK (true);
```

---

## 6. Supabase API Credentials Configuration

In Supabase Dashboard, go to **Project Settings** → **API**:
1. Copy **Project URL** (`https://[PROJECT-REF].supabase.co`).
2. Copy **Project API Key - anon/public** (Used for public operations).
3. Copy **Project API Key - service_role** (Secret! Used strictly server-side by Laravel).

Add these to your `.env`:
```ini
SUPABASE_URL=https://[PROJECT-REF].supabase.co
SUPABASE_ANON_KEY=[YOUR-ANON-KEY]
SUPABASE_SERVICE_ROLE_KEY=[YOUR-SERVICE-ROLE-KEY]

SUPABASE_STORAGE_BUCKET_PUBLIC=storpify-public-assets
SUPABASE_STORAGE_BUCKET_PRODUCTS=storpify-product-downloads
SUPABASE_STORAGE_BUCKET_SUBSCRIPTIONS=storpify-subscription-library
SUPABASE_STORAGE_BUCKET_PAYMENT_PROOFS=storpify-payment-proofs
```

> **SECURITY NOTICE**: Never commit your `.env` or Service Role key to public GitHub repositories or client-side JavaScript.
