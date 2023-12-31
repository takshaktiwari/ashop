<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Takshak\Ashop\Models\Shop\Attribute;
use Takshak\Ashop\Models\Shop\Brand;
use Takshak\Ashop\Models\Shop\Category;
use Takshak\Ashop\Models\Shop\Product;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:seed {fresh?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed every thing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('vendor:publish', [
            '--tag' => 'ashop-seeders',
            '--force' => true
        ]);
        $this->line(Artisan::output());

        if ($this->argument('fresh')) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Brand::truncate();
            Attribute::truncate();
            Category::truncate();
            Product::truncate();
        }

        $this->line('Seeding brands to the database');
        Artisan::call('db:seed', ['--class' => 'BrandSeeder']);

        $this->line('Seeding attributes to the database');
        Artisan::call('db:seed', ['--class' => 'AttributeSeeder']);

        $this->line('Seeding categories to the database');
        Artisan::call('db:seed', ['--class' => 'CategorySeeder']);

        $this->line('Seeding products to the database');
        Artisan::call('db:seed', ['--class' => 'ProductSeeder']);

        $this->newLine();
        $this->info('Database successfully seeded.');
    }
}
