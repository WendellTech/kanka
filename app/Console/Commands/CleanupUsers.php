<?php

namespace App\Console\Commands;

use App\Services\Users\PurgeService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:purge {dry=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Purge accounts';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info(Carbon::now());
        /** @var PurgeService $service */
        $service = app()->make(PurgeService::class);

        $dry = $this->argument('dry');
        if ($dry === '0') {
            $service->real();
        }

        $cutoff = Carbon::now()->subYears(1);

        $count = $service->date($cutoff)->empty();
        $this->info(Carbon::now() . ': Empty scheduled ' . $count . ' users for cleanup.');

        $cutoff = Carbon::now()->subYears(2);
        $count = $service->date($cutoff)->example();
        $this->info(Carbon::now() . ': Example  scheduled ' . $count . ' users for cleanup.');
    }
}
