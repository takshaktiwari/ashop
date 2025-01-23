<?php

namespace Takshak\Ashop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Takshak\Ashop\Console\Commands\ClearJunkOrdersCommand;
use Takshak\Ashop\Console\Commands\ProductAttachParentCategoriesCommand;
use Takshak\Ashop\Console\Commands\ProductsSearchTagsOptimizeCommand;
use Takshak\Ashop\Console\Commands\ProductsViewedDeleteCommand;
use Takshak\Ashop\Console\Commands\SeedCommand;

class AshopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ProductsSearchTagsOptimizeCommand::class,
            ProductAttachParentCategoriesCommand::class,
            SeedCommand::class,
            ClearJunkOrdersCommand::class,
            ProductsViewedDeleteCommand::class
        ]);
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
            View\Components\Ashop\BrandsGroup::class,
            View\Components\Ashop\UserAccount::class,
            View\Components\Ashop\UserBottomNav::class,
            View\Components\Ashop\ProductsViewedHistory::class
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->publishes([
            __DIR__ . '/../config/ashop.php' => config_path('ashop.php'),
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
