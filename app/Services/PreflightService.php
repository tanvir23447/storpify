<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Exception;

class PreflightService
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Run all preflight checks for Hostinger Web Hosting & Supabase compatibility.
     */
    public function runAllChecks(): array
    {
        return [
            'php' => $this->checkPhp(),
            'extensions' => $this->checkExtensions(),
            'limits' => $this->checkPhpLimits(),
            'database' => $this->checkDatabaseConnection(),
            'supabase_api' => $this->supabase->testConnection(),
            'supabase_storage' => $this->supabase->testStorageConnection(),
            'outbound_https' => $this->checkOutboundHttps(),
            'storage_permissions' => $this->checkStoragePermissions(),
        ];
    }

    public function checkPhp(): array
    {
        $version = PHP_VERSION;
        $compatible = version_compare($version, '8.2.0', '>=');

        return [
            'version' => $version,
            'compatible' => $compatible,
            'status' => $compatible ? 'pass' : 'fail',
            'message' => $compatible
                ? "PHP {$version} satisfies Laravel requirements (>= 8.2)."
                : "PHP {$version} is below 8.2. Please switch PHP version to 8.2 or 8.3 in Hostinger hPanel.",
        ];
    }

    public function checkExtensions(): array
    {
        $required = [
            'pdo_pgsql' => 'PostgreSQL PDO driver for direct Supabase database queries',
            'pgsql' => 'PostgreSQL core driver',
            'curl' => 'cURL for Supabase REST & Payment APIs (bKash, SSLCOMMERZ)',
            'openssl' => 'OpenSSL for secure token generation & HTTPS encryption',
            'mbstring' => 'Mbstring for Bangla Unicode text handling',
            'fileinfo' => 'Fileinfo for secure MIME-type detection of digital assets',
            'gd' => 'GD extension for thumbnail and image processing',
            'zip' => 'Zip extension for handling digital download archives',
        ];

        $results = [];
        $allPassed = true;

        foreach ($required as $ext => $description) {
            $loaded = extension_loaded($ext);
            if (!$loaded) {
                $allPassed = false;
            }
            $results[$ext] = [
                'loaded' => $loaded,
                'description' => $description,
            ];
        }

        return [
            'all_passed' => $allPassed,
            'details' => $results,
            'recommendation' => $allPassed
                ? 'All mandatory extensions are present.'
                : 'Enable missing extensions in Hostinger hPanel -> Advanced -> PHP Extensions.',
        ];
    }

    public function checkPhpLimits(): array
    {
        return [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time') . 's',
        ];
    }

    public function checkDatabaseConnection(): array
    {
        try {
            $connectionName = config('database.default');
            DB::connection()->getPdo();
            $driver = DB::connection()->getDriverName();

            return [
                'connected' => true,
                'driver' => $driver,
                'connection_name' => $connectionName,
                'message' => "Successfully connected using {$driver} driver.",
            ];
        } catch (Exception $e) {
            return [
                'connected' => false,
                'message' => 'Database connection failed: ' . $e->getMessage(),
            ];
        }
    }

    public function checkOutboundHttps(): array
    {
        try {
            $res = Http::timeout(5)->get('https://www.google.com');
            return [
                'success' => $res->successful(),
                'status' => $res->status(),
                'message' => 'Outbound HTTPS connectivity is functional.',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Outbound HTTPS request failed: ' . $e->getMessage(),
            ];
        }
    }

    public function checkStoragePermissions(): array
    {
        $paths = [
            storage_path(),
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        $results = [];
        $allWritable = true;

        foreach ($paths as $path) {
            $writable = is_writable($path);
            if (!$writable) {
                $allWritable = false;
            }
            $results[$path] = $writable;
        }

        return [
            'all_writable' => $allWritable,
            'paths' => $results,
        ];
    }
}
