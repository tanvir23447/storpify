<?php

namespace App\Console\Commands;

use App\Services\PreflightService;
use Illuminate\Console\Command;

class PreflightCommand extends Command
{
    protected $signature = 'storpify:preflight';
    protected $description = 'Perform Hostinger Web Hosting & Supabase PostgreSQL compatibility verification';

    public function handle(PreflightService $preflight): int
    {
        $this->info("============================================================");
        $this->info("STORPIFY PREFLIGHT & HOSTINGER COMPATIBILITY GATEWAY");
        $this->info("============================================================");

        $results = $preflight->runAllChecks();

        // 1. PHP Version
        $php = $results['php'];
        $this->line("\n[1] PHP Environment:");
        $this->line("    Version: {$php['version']}");
        if ($php['compatible']) {
            $this->info("    ✓ {$php['message']}");
        } else {
            $this->error("    ✗ {$php['message']}");
        }

        // 2. Extensions
        $ext = $results['extensions'];
        $this->line("\n[2] PHP Extensions:");
        foreach ($ext['details'] as $name => $item) {
            if ($item['loaded']) {
                $this->info("    ✓ {$name}: loaded ({$item['description']})");
            } else {
                $this->warn("    ✗ {$name}: NOT loaded ({$item['description']})");
            }
        }

        // 3. Limits
        $limits = $results['limits'];
        $this->line("\n[3] PHP Runtime Limits:");
        $this->line("    upload_max_filesize: {$limits['upload_max_filesize']}");
        $this->line("    post_max_size:       {$limits['post_max_size']}");
        $this->line("    memory_limit:        {$limits['memory_limit']}");
        $this->line("    max_execution_time:  {$limits['max_execution_time']}");

        // 4. Database
        $db = $results['database'];
        $this->line("\n[4] Active Database Connection:");
        if ($db['connected']) {
            $this->info("    ✓ {$db['message']}");
        } else {
            $this->error("    ✗ {$db['message']}");
        }

        // 5. Supabase REST API
        $supa = $results['supabase_api'];
        $this->line("\n[5] Supabase Data API Check:");
        if ($supa['success']) {
            $this->info("    ✓ {$supa['message']}");
        } else {
            $this->warn("    ℹ {$supa['message']}");
        }

        // 6. Supabase Storage
        $storage = $results['supabase_storage'];
        $this->line("\n[6] Supabase Storage Check:");
        if ($storage['success']) {
            $this->info("    ✓ {$storage['message']}");
        } else {
            $this->warn("    ℹ {$storage['message']}");
        }

        // 7. Outbound HTTPS
        $https = $results['outbound_https'];
        $this->line("\n[7] Outbound HTTPS Connectivity:");
        if ($https['success']) {
            $this->info("    ✓ {$https['message']}");
        } else {
            $this->error("    ✗ {$https['message']}");
        }

        $this->info("\n============================================================");
        $this->info("PREFLIGHT COMPLETED.");
        $this->info("============================================================");

        return 0;
    }
}
