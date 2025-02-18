<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class UpdateDeliveryStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-delivery-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update order status to FULFILLED for completed deliveries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Order::where('status', 'UNDER_DELIVERY')
            ->whereHas('delivery', function ($query) {
                $query->whereDate('delivery_date', '<=', Carbon::today());
            })
            ->update(['status' => 'FULFILLED']);

        $this->info('Delivery statuses updated successfully');
    }
}
