<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Use public MySQL URL if available (Railway fix)
        if (env('MYSQL_PUBLIC_URL')) {
            $url = parse_url(env('MYSQL_PUBLIC_URL'));
            
            config([
                'database.connections.mysql.host' => $url['host'] ?? null,
                'database.connections.mysql.port' => $url['port'] ?? 3306,
                'database.connections.mysql.database' => trim($url['path'] ?? '', '/'),
                'database.connections.mysql.username' => $url['user'] ?? null,
                'database.connections.mysql.password' => $url['pass'] ?? null,
            ]);
        }
    }
}