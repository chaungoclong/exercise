<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // blade directive for checked, selected and actived
        $props = ['checked', 'selected', 'actived', 'disabled'];

        foreach ($props as $prop) {
            Blade::directive($prop, function ($value) use ($prop) {
                return "<?php if($value) echo '$prop'; ?>";
            });
        }

        Blade::directive('showError', function ($parameters) {
            return "<?php echo displayErrorInput($parameters) ?>";
        });


        // Format Money VNĐ
        Blade::directive('vnd', function ($value) {
            return "<?php echo number_format($value, 2, ',', ' ') . ' đ'; ?>";
        });
    }
}
