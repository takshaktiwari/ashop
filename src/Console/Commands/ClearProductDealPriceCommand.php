<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Order;
use Takshak\Ashop\Models\Shop\Product;

use function Laravel\Prompts\info;

class ClearProductDealPriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:clear-products-deal-price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear products deal price if deal is expired';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = Product::query()
            ->select('id', 'deal_price', 'deal_expiry')
            ->where('deal_expiry', '<', now());

        $count = (clone $query)->count();
        info($count . ' products deal price has been cleared.');

        if ($count) {
            $query->update([
                'deal_price' => null,
                'deal_expiry' => null,
            ]);
        }
    }
}
