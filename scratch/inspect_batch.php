<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\HarvestBatch;
use App\Models\PreOrder;
use App\Models\CommodityListing;

echo "--- LISTINGS CABAI MERAH KERITING KASTILO ---\n";
$listings = CommodityListing::with(['harvestBatches.preOrders'])->where('commodity_name', 'like', '%Cabai Merah Keriting Kastilo%')->get();
foreach ($listings as $l) {
    echo "Listing ID: {$l->id} | Name: {$l->commodity_name} | Est: {$l->estimated_harvest_kg} | Avail: {$l->available_kg}\n";
    foreach ($l->harvestBatches as $b) {
        echo "   -> Batch ID: {$b->id} | Code: {$b->batch_code} | Name: {$b->batch_name} | Est: {$b->estimated_harvest_kg} | Alloc: {$b->allocated_kg} | OrderedKg: {$b->ordered_kg} | AvailKg: {$b->available_kg} | PO count: {$b->preOrders->count()}\n";
        foreach ($b->preOrders as $po) {
            echo "        * PO ID: {$po->id} | Code: {$po->order_code} | Qty: {$po->quantity_kg} | Status: {$po->status}\n";
        }
    }
}

echo "\n--- ANY BATCH WITH 2100 kg? ---\n";
$b2100 = HarvestBatch::where('estimated_harvest_kg', 2100)->get();
foreach ($b2100 as $b) {
    echo "Batch 2100: ID: {$b->id} | Code: {$b->batch_code} | Name: {$b->batch_name}\n";
}
