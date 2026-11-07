<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PreOrder;
use App\Http\Controllers\TaniLinkController;
use Illuminate\Http\Request;

$ctrl = new TaniLinkController();
$req = new Request(['period_type' => 'tahunan', 'year' => 2026]);

$ref = new ReflectionClass(TaniLinkController::class);
$parseReportFilters = $ref->getMethod('parseReportFilters');
$parseReportFilters->setAccessible(true);
$filters = $parseReportFilters->invoke($ctrl, $req);

$getReportPeriodRange = $ref->getMethod('getReportPeriodRange');
$getReportPeriodRange->setAccessible(true);
[$periodStart, $periodEnd] = $getReportPeriodRange->invoke($ctrl, $filters);

$getReportChartData = $ref->getMethod('getReportChartData');
$getReportChartData->setAccessible(true);
$chartData = $getReportChartData->invoke($ctrl, $filters, $periodStart, $periodEnd);

echo "Timeline Labels: " . json_encode($chartData['timeline_labels']) . "\n";
echo "Timeline Orders: " . json_encode($chartData['timeline_orders']) . "\n";
echo "Timeline Values: " . json_encode($chartData['timeline_values']) . "\n";
