<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Throwable;

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
        Paginator::useBootstrap();
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('layouts.app', function ($view): void {
            $maintenanceMessage = null;

            try {
                if (Schema::hasTable('maintenance_message_settings')) {
                    $setting = DB::table('maintenance_message_settings')
                        ->where('id', 1)
                        ->first(['is_enabled', 'maintenance_at', 'duration_minutes', 'message_template']);

                    if (
                        $setting
                        && (int) $setting->is_enabled === 1
                        && !empty($setting->maintenance_at)
                        && (int) $setting->duration_minutes > 0
                    ) {
                        $maintenanceAt = Carbon::parse($setting->maintenance_at)->format('d-m-Y H:i');
                        $durationMinutes = (int) $setting->duration_minutes;
                        $defaultTemplate = 'Dalam rangka peningkatan kualitas layanan dan performa sistem, website kami akan menjalani maintenance pada [maintenance_at] WIB selama [duration_minutes] menit, sehingga untuk sementara tidak dapat diakses, mohon maaf atas ketidaknyamanannya.';
                        $template = trim((string) ($setting->message_template ?? ''));
                        if ($template === '') {
                            $template = $defaultTemplate;
                        }

                        $maintenanceMessage = str_replace(
                            ['[maintenance_at]', '{maintenance_at}', '[duration_minutes]', '{duration_minutes}'],
                            [$maintenanceAt, $maintenanceAt, (string) $durationMinutes, (string) $durationMinutes],
                            $template
                        );
                    }
                }
            } catch (Throwable $e) {
                $maintenanceMessage = null;
            }

            $view->with('maintenanceMessage', $maintenanceMessage);
        });
    }
}
