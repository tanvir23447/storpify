import { z } from 'zod';

const clientEnvSchema = z.object({
  NEXT_PUBLIC_SITE_URL: z.string().url().default('http://localhost:3000'),
  NEXT_PUBLIC_SUPABASE_URL: z.string().optional().default(''),
  NEXT_PUBLIC_SUPABASE_ANON_KEY: z.string().optional().default(''),
  NEXT_PUBLIC_META_PIXEL_ID: z.string().optional().default(''),
  NEXT_PUBLIC_GA_MEASUREMENT_ID: z.string().optional().default(''),
  WHATSAPP_BUSINESS_NUMBER: z.string().optional().default('+8801700000000'),
});

const serverEnvSchema = clientEnvSchema.extend({
  SUPABASE_SERVICE_ROLE_KEY: z.string().optional().default(''),
  ADMIN_BOOTSTRAP_SECRET: z.string().optional().default(''),
  BKASH_PAYMENT_NUMBER: z.string().default('01700000000'),
  NAGAD_PAYMENT_NUMBER: z.string().default('01800000000'),
  ROCKET_PAYMENT_NUMBER: z.string().default('01900000000'),
  PAYMENT_GATEWAY_PUBLIC_KEY: z.string().optional().default(''),
  PAYMENT_GATEWAY_SECRET_KEY: z.string().optional().default(''),
  EMAIL_PROVIDER_API_KEY: z.string().optional().default(''),
  EMAIL_FROM_ADDRESS: z.string().email().optional().default('orders@maisonelora.com'),
});

export type ClientEnv = z.infer<typeof clientEnvSchema>;
export type ServerEnv = z.infer<typeof serverEnvSchema>;

export function getClientEnv(): ClientEnv {
  return clientEnvSchema.parse({
    NEXT_PUBLIC_SITE_URL: process.env.NEXT_PUBLIC_SITE_URL,
    NEXT_PUBLIC_SUPABASE_URL: process.env.NEXT_PUBLIC_SUPABASE_URL,
    NEXT_PUBLIC_SUPABASE_ANON_KEY: process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY,
    NEXT_PUBLIC_META_PIXEL_ID: process.env.NEXT_PUBLIC_META_PIXEL_ID,
    NEXT_PUBLIC_GA_MEASUREMENT_ID: process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID,
    WHATSAPP_BUSINESS_NUMBER: process.env.WHATSAPP_BUSINESS_NUMBER || process.env.NEXT_PUBLIC_WHATSAPP_BUSINESS_NUMBER,
  });
}

export function getServerEnv(): ServerEnv {
  return serverEnvSchema.parse({
    NEXT_PUBLIC_SITE_URL: process.env.NEXT_PUBLIC_SITE_URL || process.env.SITE_URL,
    NEXT_PUBLIC_SUPABASE_URL: process.env.NEXT_PUBLIC_SUPABASE_URL,
    NEXT_PUBLIC_SUPABASE_ANON_KEY: process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY,
    SUPABASE_SERVICE_ROLE_KEY: process.env.SUPABASE_SERVICE_ROLE_KEY,
    ADMIN_BOOTSTRAP_SECRET: process.env.ADMIN_BOOTSTRAP_SECRET,
    BKASH_PAYMENT_NUMBER: process.env.BKASH_PAYMENT_NUMBER,
    NAGAD_PAYMENT_NUMBER: process.env.NAGAD_PAYMENT_NUMBER,
    ROCKET_PAYMENT_NUMBER: process.env.ROCKET_PAYMENT_NUMBER,
    PAYMENT_GATEWAY_PUBLIC_KEY: process.env.PAYMENT_GATEWAY_PUBLIC_KEY,
    PAYMENT_GATEWAY_SECRET_KEY: process.env.PAYMENT_GATEWAY_SECRET_KEY,
    EMAIL_PROVIDER_API_KEY: process.env.EMAIL_PROVIDER_API_KEY,
    EMAIL_FROM_ADDRESS: process.env.EMAIL_FROM_ADDRESS,
    NEXT_PUBLIC_META_PIXEL_ID: process.env.NEXT_PUBLIC_META_PIXEL_ID,
    NEXT_PUBLIC_GA_MEASUREMENT_ID: process.env.NEXT_PUBLIC_GA_MEASUREMENT_ID,
    WHATSAPP_BUSINESS_NUMBER: process.env.WHATSAPP_BUSINESS_NUMBER,
  });
}

export function isSupabaseConfigured(): boolean {
  const url = process.env.NEXT_PUBLIC_SUPABASE_URL;
  const anonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;
  return Boolean(
    url &&
    url.startsWith('https://') &&
    !url.includes('YOUR_PROJECT_REF') &&
    anonKey &&
    anonKey.length > 20 &&
    !anonKey.includes('YOUR_PUBLIC_PUBLISHABLE_OR_ANON_KEY')
  );
}

export const env = typeof window === 'undefined' ? getServerEnv() : (getClientEnv() as ServerEnv);
