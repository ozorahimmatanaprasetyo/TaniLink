<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CommodityListing;
use App\Models\User;

$petani = User::where('role', 'petani')->first();
try {
    $listing = CommodityListing::create([
        'user_id' => $petani->id,
        'commodity_name' => 'Test Sawi Grade Null',
        'category' => 'Sayuran',
        'variety' => 'Test Variety',
        'grade' => null, // Test nullable
        'land_area_m2' => 1000,
        'estimated_harvest_kg' => 500,
        'available_kg' => 500,
        'price_per_kg' => 10000,
        'harvest_estimated_date' => now()->addDays(30)->toDateString(),
        'location' => 'Malang',
        'status' => 'available',
    ]);
    echo "SUCCESS: Created listing ID: {$listing->id} with grade: " . var_export($listing->grade, true) . "\n";
    $listing->delete();
    echo "Cleaned up test listing.\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
}
