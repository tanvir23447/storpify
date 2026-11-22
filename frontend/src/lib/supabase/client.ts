import { createClient } from '@supabase/supabase-js';
import { isSupabaseConfigured } from '../env';

let supabaseClient: any = null;

export function getSupabaseBrowserClient() {
  if (supabaseClient) return supabaseClient;

  if (isSupabaseConfigured()) {
    const url = process.env.NEXT_PUBLIC_SUPABASE_URL!;
    const anonKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY!;
    supabaseClient = createClient(url, anonKey);
    return supabaseClient;
  }

  // Graceful fallback mock client when credentials not set locally
  return null;
}
