<?php

namespace Takshak\Ashop\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Takshak\Ashop\Models\Shop\ProductViewed;

use function Laravel\Prompts\info;
use function Laravel\Prompts\note;

class ProductsViewedDeleteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ashop:products-viewed-delete {--keep=50}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete products viewed history after keeping some of them';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersWithExcessRecords = ProductViewed::select('user_id', DB::raw('COUNT(*) as count'))
            ->groupBy('user_id')
            ->having('count', '>', 50)
            ->pluck('user_id');

        info('Found ' . $usersWithExcessRecords->count() . ' users with excess records');

        $totalRecords = 0;
        foreach ($usersWithExcessRecords as $userId) {
            $idsToDelete = ProductViewed::where('user_id', $userId)
                ->orderBy('created_at') // Ensure proper ordering by timestamp
                ->skip(50)
                ->take(PHP_INT_MAX) // Take all remaining records after skipping the latest 50
                ->pluck('id');

            ProductViewed::whereIn('id', $idsToDelete)->delete();
            $user = User::find($userId);
            $this->line($idsToDelete->count() . ' records deleted for user: ' . $user?->name);

            $totalRecords += $idsToDelete->count();
        }

        info('Deleted ' . $totalRecords . ' records');
        return Command::SUCCESS;
    }
}
