<?php

namespace Takshak\Ashop\Console\Commands;

use Illuminate\Console\Command;
use Takshak\Ashop\Models\Shop\Order;

class ClearJunkOrdersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:clear-junk-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear those orders which is not successfully placed or not valid';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $query = Order::query()
            ->whereNull('order_status')
            ->orWhereNull('payment_mode')
            ->orWhereNull('order_no')
            ->orWhere(function ($query) {
                $query->doesntHave('orderProducts');
            })
            ->where('created_at', '>', now()->addHour());

        $junkOrdersCount = (clone $query)->count();
        $query->delete();
        $this->info($junkOrdersCount . ' Junk orders has been deleted');
    }
}
