<?php

namespace Takshak\Ashop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Takshak\Ashop\Console\Commands\ProductsSearchTagsOptimizeCommand;

class AshopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([ProductsSearchTagsOptimizeCommand::class]);
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ashop');
        $this->loadViewComponentsAs('ashop', [
            View\Components\Ashop\AdminSidebarLinks::class,
            View\Components\Ashop\InnerNav::class,
            View\Components\Ashop\ProductNav::class,
            View\Components\Ashop\ProductCard::class,
            View\Components\Ashop\ProductListItem::class,
            View\Components\Ashop\ShopSidebar::class,
            View\Components\Ashop\ShopHeader::class,
            View\Components\Ashop\ProductsGroup::class,
            View\Components\Ashop\CategoriesGroup::class,
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->publishes([
            __DIR__ . '/../routes/ashop.php' => config_path('ashop.php'),
        ], 'ashop-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views'),
        ], 'ashop-views');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('assets/ashop'),
        ], 'ashop-assets');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'ashop-seeders');

        Paginator::useBootstrap();
    }

}
