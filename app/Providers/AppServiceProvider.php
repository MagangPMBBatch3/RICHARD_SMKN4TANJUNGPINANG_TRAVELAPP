<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\App;
use Exception;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::runningInConsole()) {
            try {
                DB::connection()->getPdo();
                $dbName = DB::connection()->getDatabaseName();
                echo " Koneksi ke database berhasil: $dbName\n ";
            } catch (Exception $e){
                echo " Koneksi ke database gagal: " . $e->getMessage() . "\n";
                Log::error(" Koneksi database gagal: " . $e->getMessage());
                exit(1);
            }
        }
    }
}
