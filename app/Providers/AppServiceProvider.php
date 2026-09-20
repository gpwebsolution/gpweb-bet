<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;


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
        try {
            $setting = \Helper::getSetting();
            config(['setting' => is_array($setting) ? $setting : (is_object($setting) && method_exists($setting, 'toArray') ? $setting->toArray() : (array) $setting)]);
            View::share('setting', $setting);
        } catch (\Throwable $e) {
            $fallback = [
                'software_name' => 'MarioBET',
                'software_description' => '',
                'prefix' => 'R$',
                'min_deposit' => 10,
                'max_deposit' => 99999,
                'min_saque' => 10,
                'max_saque' => 99999,
                'initial_bonus' => 50,
                'affiliate_default_cpa' => 40,
                'affiliate_default_baseline' => 70,
            ];
            config(['setting' => $fallback]);
            View::share('setting', null);
        }

        Paginator::useBootstrapFive();
        Schema::defaultStringLength(191);

        Builder::macro('whereLike', function ($attributes, string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach (Arr::wrap($attributes) as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $buffer = explode('.', $attribute);
                            $attributeField = array_pop($buffer);
                            $relationPath = implode('.', $buffer);
                            $query->orWhereHas($relationPath, function (Builder $query) use ($attributeField, $searchTerm) {
                                $query->where($attributeField, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
            return $this;
        });
    }
}
