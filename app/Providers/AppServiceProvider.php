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
        // Railway MySQL connection fix - try public URL if internal fails
        if (env('MYSQL_PUBLIC_URL') && env('DB_CONNECTION') === 'mysql') {
            try {
                $url = parse_url(env('MYSQL_PUBLIC_URL'));
                
                if ($url && isset($url['host'])) {
                    config([
                        'database.connections.mysql.host' => $url['host'],
                        'database.connections.mysql.port' => $url['port'] ?? 3306,
                        'database.connections.mysql.database' => trim($url['path'] ?? '', '/'),
                        'database.connections.mysql.username' => $url['user'] ?? '',
                        'database.connections.mysql.password' => $url['pass'] ?? '',
                    ]);
                }
            } catch (\Exception $e) {
                // Log error but don't break the application
                \Log::error('Failed to parse MYSQL_PUBLIC_URL: ' . $e->getMessage());
            }
        }
    }
}