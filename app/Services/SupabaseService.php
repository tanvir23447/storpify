<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class SupabaseService
{
    protected ?string $url;
    protected ?string $anonKey;
    protected ?string $serviceRoleKey;

    public function __construct()
    {
        $this->url = rtrim(config('storpify.supabase.url') ?? '', '/');
        $this->anonKey = config('storpify.supabase.anon_key');
        $this->serviceRoleKey = config('storpify.supabase.service_role_key') ?: config('storpify.supabase.secret_key');
    }

    /**
     * Check if Supabase credentials are configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->url) && (!empty($this->anonKey) || !empty($this->serviceRoleKey));
    }

    /**
     * Get preferred key for server-side requests (service role preferred for admin/backend).
     */
    protected function getKey(): ?string
    {
        return $this->serviceRoleKey ?: $this->anonKey;
    }

    /**
     * Test connection to Supabase REST endpoint.
     */
    public function testConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Supabase URL or API Keys are not configured in .env',
            ];
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->getKey(),
                'Authorization' => 'Bearer ' . $this->getKey(),
            ])->timeout(8)->get("{$this->url}/rest/v1/");

            if ($response->successful() || $response->status() === 200) {
                return [
                    'success' => true,
                    'status' => $response->status(),
                    'message' => 'Supabase REST API connected successfully.',
                ];
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'message' => 'Supabase REST API returned: ' . $response->body(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Test connection to Supabase Storage.
     */
    public function testStorageConnection(): array
    {
        if (!$this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Supabase credentials not configured.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->getKey(),
                'Authorization' => 'Bearer ' . $this->getKey(),
            ])->timeout(8)->get("{$this->url}/storage/v1/bucket");

            if ($response->successful()) {
                $buckets = $response->json();
                return [
                    'success' => true,
                    'buckets' => array_column($buckets, 'id'),
                    'message' => 'Supabase Storage connected. Found ' . count($buckets) . ' buckets.',
                ];
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'message' => 'Storage check failed: ' . $response->body(),
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Storage connection error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Upload a file to Supabase Storage bucket.
     */
    public function uploadFile(string $bucket, string $path, string $fileContents, string $mimeType = 'application/octet-stream', bool $upsert = true): array
    {
        if (!$this->isConfigured()) {
            // Local fallback: save locally if Supabase not configured
            $localPath = "supabase_storage/{$bucket}/{$path}";
            \Illuminate\Support\Facades\Storage::disk('local')->put($localPath, $fileContents);
            return [
                'success' => true,
                'mode' => 'local_fallback',
                'path' => $localPath,
                'bucket' => $bucket,
            ];
        }

        try {
            $endpoint = "{$this->url}/storage/v1/object/{$bucket}/" . ltrim($path, '/');
            
            $response = Http::withHeaders([
                'apikey' => $this->getKey(),
                'Authorization' => 'Bearer ' . $this->getKey(),
                'Content-Type' => $mimeType,
                'x-upsert' => $upsert ? 'true' : 'false',
            ])->withBody($fileContents, $mimeType)->post($endpoint);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'key' => $response->json('Key') ?? "{$bucket}/{$path}",
                    'bucket' => $bucket,
                    'path' => $path,
                ];
            }

            return [
                'success' => false,
                'error' => $response->body(),
            ];
        } catch (Exception $e) {
            Log::error("Supabase Storage Upload Error: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate a signed temporary download URL for private files.
     */
    public function createSignedUrl(string $bucket, string $path, int $expiresIn = 3600): ?string
    {
        if (!$this->isConfigured()) {
            // In local environment fallback, return a secure internal route
            return route('secure.download', ['bucket' => $bucket, 'path' => base64_encode($path), 'expires' => time() + $expiresIn]);
        }

        try {
            $endpoint = "{$this->url}/storage/v1/object/sign/{$bucket}/" . ltrim($path, '/');
            $response = Http::withHeaders([
                'apikey' => $this->getKey(),
                'Authorization' => 'Bearer ' . $this->getKey(),
                'Content-Type' => 'application/json',
            ])->post($endpoint, [
                'expiresIn' => $expiresIn,
            ]);

            if ($response->successful()) {
                $signedPath = $response->json('signedURL');
                return "{$this->url}/storage/v1{$signedPath}";
            }

            Log::warning("Failed to generate Supabase signed URL: " . $response->body());
            return null;
        } catch (Exception $e) {
            Log::error("Supabase Signed URL Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get public URL for public bucket assets.
     */
    public function getPublicUrl(string $bucket, string $path): string
    {
        if (!$this->isConfigured()) {
            return asset("storage/{$bucket}/{$path}");
        }

        return "{$this->url}/storage/v1/object/public/{$bucket}/" . ltrim($path, '/');
    }
}
