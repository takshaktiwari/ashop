<?php

namespace Takshak\Ashop;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Takshak\Ashop\Console\Commands\ClearJunkOrdersCommand;
use Takshak\Ashop\Console\Commands\ProductAttachParentCategoriesCommand;
use Takshak\Ashop\Console\Commands\ProductsSearchTagsOptimizeCommand;
use Takshak\Ashop\Console\Commands\SeedCommand;

class AshopServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            ProductsSearchTagsOptimizeCommand::class,
            ProductAttachParentCategoriesCommand::class,
            SeedCommand::class,
            ClearJunkOrdersCommand::class
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
        ]);

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
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

        Blade::directive('dataTableAssets', function ($expression) {
            return '<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.2/css/responsive.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.5.0/css/rowGroup.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/searchbuilder/1.7.1/css/searchBuilder.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.3.1/css/searchPanes.bootstrap5.min.css">
                    <link rel="stylesheet" href="https://cdn.datatables.net/select/2.0.3/css/select.bootstrap5.min.css">

                    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>

                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.flash.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap5.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
                    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

                    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>
                    <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>
                    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/dataTables.rowGroup.min.js"></script>
                    <script src="https://cdn.datatables.net/rowgroup/1.5.0/js/rowGroup.bootstrap5.min.js"></script>
                    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/dataTables.searchBuilder.min.js"></script>
                    <script src="https://cdn.datatables.net/searchbuilder/1.7.1/js/searchBuilder.bootstrap5.min.js"></script>
                    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/dataTables.searchPanes.min.js"></script>
                    <script src="https://cdn.datatables.net/searchpanes/2.3.1/js/searchPanes.bootstrap5.min.js"></script>
                    <script src="https://cdn.datatables.net/select/2.0.3/js/dataTables.select.min.js"></script>
                    <script src="https://cdn.datatables.net/select/2.0.3/js/select.bootstrap5.min.js"></script>';
        });
    }

}
