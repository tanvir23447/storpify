# Maison Elora – Environment & Setup Guide
## Premium Beauty & Care E-commerce Ecosystem

This guide explains how to set up, configure, and run the **Maison Elora** e-commerce platform locally and prepare it for production on Supabase and Vercel.

---

### Security First Notice
> **CRITICAL SECURITY RULE:**
> - Never commit `.env` or `.env.local` files to Git.
> - Never paste real service role secrets or API keys into public forums, AI chats, or client-side bundles.
> - The application operates in **Safe Fallback Mode** out of the box until real Supabase credentials are provided.

---

## 1. Quick Start (Local Development)

### Step 1: Copy Environment Template
Copy the environment template to create your local environment file:

```powershell
Copy-Item .env.example .env.local
# In the frontend directory:
Copy-Item frontend\.env.example frontend\.env.local
```

### Step 2: Install Dependencies
Run in the `frontend` directory:
```powershell
cd frontend
npm install
```

### Step 3: Run the Development Server
```powershell
npm run dev
```
Open [http://localhost:3000](http://localhost:3000) in your browser.

---

## 2. Setting Up Supabase (Production or Staging)

1. Create a free or pro project at [database.new](https://database.new) or [supabase.com](https://supabase.com).
2. Go to **Project Settings** → **API**:
   - Copy **Project URL** into `NEXT_PUBLIC_SUPABASE_URL`
   - Copy **anon public key** into `NEXT_PUBLIC_SUPABASE_ANON_KEY`
   - Copy **service_role secret key** into `SUPABASE_SERVICE_ROLE_KEY` (Server-only!)
3. In Supabase Dashboard, open **SQL Editor**:
   - Run the initial schema migration from `supabase/migrations/001_initial_schema.sql`.
   - Run the seed data from `supabase/seed/seed_data.sql`.
4. Open **Storage** in Supabase:
   - Ensure the `brand-assets` and `product-media` buckets are created (the migration creates them automatically with appropriate public/authenticated policies).

---

## 3. Environment Variables Reference

| Variable | Scope | Description |
|---|---|---|
| `NEXT_PUBLIC_SITE_URL` | Public / Browser | Canonical base URL (e.g. `http://localhost:3000` or `https://maisonelora.com`) |
| `NEXT_PUBLIC_SUPABASE_URL` | Public / Browser | Supabase project API endpoint |
| `NEXT_PUBLIC_SUPABASE_ANON_KEY` | Public / Browser | Supabase publishable anonymous key (governed by Row Level Security) |
| `SUPABASE_SERVICE_ROLE_KEY` | **Server Only** | Administrative key for server tasks, migrations, and elevated webhooks |
| `ADMIN_BOOTSTRAP_SECRET` | **Server Only** | Secret token to initialize first super admin account |
| `BKASH_PAYMENT_NUMBER` | Server / Admin | Official bKash merchant or personal wallet number |
| `NAGAD_PAYMENT_NUMBER` | Server / Admin | Official Nagad wallet number |
| `ROCKET_PAYMENT_NUMBER` | Server / Admin | Official Rocket wallet number |
| `WHATSAPP_BUSINESS_NUMBER` | Public / Browser | Customer support WhatsApp hotline number |

---

## 4. Super Admin Credentials & Bootstrap

By default in mock/local mode:
- **Admin URL:** `/admin`
- **Default Role:** Super Admin (unrestricted access to brand identity, inventory, expiry, FEFO batches, orders, CMS)
- **Live Logo Replacement:** Admin → Settings → Brand Identity & Logo Management

---

## 5. Deployment to Vercel

1. Push your repository to GitHub / GitLab (ensure `.gitignore` excludes `.env.local`).
2. Import the project into [Vercel](https://vercel.com).
3. If deploying from a subfolder, set Root Directory to `frontend`.
4. Add the Environment Variables from your `.env.local` to Vercel Project Settings.
5. Deploy! Vercel provides automatic SSL, CDN edge caching, and Next.js ISR support.
