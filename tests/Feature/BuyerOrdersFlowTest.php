<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\PreOrder;
use App\Models\HarvestBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuyerOrdersFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_order_creation_appears_in_buyer_orders_and_farmer_orders()
    {
        // 1. Create Farmer & Buyer
        $farmer = User::factory()->create([
            'role' => 'petani',
            'name' => 'Petani Subur',
            'bank_name' => 'BCA',
            'bank_account' => '1234567890',
            'bank_holder' => 'Petani Subur',
        ]);

        $buyer = User::factory()->create([
            'role' => 'pembeli',
            'name' => 'Konsumen Hebat',
        ]);

        // 2. Create Commodity Listing & Batch
        $listing = CommodityListing::create([
            'user_id' => $farmer->id,
            'commodity_name' => 'Tomat Ceri Hidroponik Ruby',
            'category' => 'Sayuran',
            'variety' => 'Ruby Red',
            'location' => 'Bandung, Jawa Barat',
            'land_area_m2' => 1500,
            'price_per_kg' => 20000,
            'estimated_harvest_kg' => 500,
            'available_kg' => 500,
            'harvest_estimated_date' => now()->addDays(40),
            'status' => 'available',
        ]);

        $batch = HarvestBatch::create([
            'commodity_listing_id' => $listing->id,
            'farmer_id' => $farmer->id,
            'batch_code' => 'BATCH-TEST-01',
            'batch_name' => 'Batch Panen Des 2026',
            'estimated_harvest_date' => now()->addDays(40),
            'estimated_harvest_kg' => 500,
            'allocated_kg' => 0,
            'current_phase' => 'Persiapan & Penanaman',
            'current_phase_status' => 'berlangsung',
            'status' => 'aktif',
        ]);

        // 3. Buyer submits booking from marketplace
        $bookingResponse = $this->actingAs($buyer)->post(route('pembeli.booking.store'), [
            'listing_id' => $listing->id,
            'quantity_kg' => 50,
            'need_date' => now()->addDays(40)->format('Y-m-d'),
            'notes' => 'Tolong sortir kualitas grade A.',
        ]);

        $bookingResponse->assertRedirect(route('pembeli.pesanan'));
        $bookingResponse->assertSessionHas('success');

        // Check database
        $order = PreOrder::where('buyer_id', $buyer->id)->where('commodity_listing_id', $listing->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(50, $order->quantity_kg);
        $this->assertEquals(1000000, $order->total_amount); // 50 * 20000
        $this->assertEquals(300000, $order->dp_amount); // 30%
        $this->assertEquals('menunggu_konfirmasi', $order->status);

        // 4. Check Buyer Orders Page (/pembeli/pesanan)
        $buyerOrdersPage = $this->actingAs($buyer)->get(route('pembeli.pesanan'));
        $buyerOrdersPage->assertStatus(200);
        $buyerOrdersPage->assertSee($order->order_code);
        $buyerOrdersPage->assertSee('Tomat Ceri Hidroponik Ruby');
        $buyerOrdersPage->assertSee('Petani Subur');
        $buyerOrdersPage->assertSee('Rp 1.000.000');
        $buyerOrdersPage->assertSee('Menunggu Konfirmasi');

        // 5. Check Farmer Orders Page (/petani/pesanan)
        $farmerOrdersPage = $this->actingAs($farmer)->get(route('petani.pesanan'));
        $farmerOrdersPage->assertStatus(200);
        $farmerOrdersPage->assertSee($order->order_code);
        $farmerOrdersPage->assertSee('Tomat Ceri Hidroponik Ruby');
        $farmerOrdersPage->assertSee('Konsumen Hebat');

        // 6. Farmer confirms the order
        $confirmResponse = $this->actingAs($farmer)->post(route('petani.pesanan.confirm', $order->id));
        $confirmResponse->assertSessionHas('success');

        $order->refresh();
        $this->assertEquals('menunggu_pembayaran_dp', $order->status);

        // 7. Buyer checks detail / tracking page (/pembeli/pesanan/{id}/tracking)
        $trackingPage = $this->actingAs($buyer)->get(route('pembeli.tracking', $order->id));
        $trackingPage->assertStatus(200);
        $trackingPage->assertSee($order->order_code);
        $trackingPage->assertSee('Menunggu Pembayaran DP');
        $trackingPage->assertSee('BCA');
        $trackingPage->assertSee('1234567890');

        // 8. Security test: Other buyer cannot access tracking page
        $otherBuyer = User::factory()->create(['role' => 'pembeli', 'name' => 'Pembeli Lain']);
        $forbiddenResponse = $this->actingAs($otherBuyer)->get(route('pembeli.tracking', $order->id));
        $forbiddenResponse->assertStatus(404); // Scoped to buyer_id
    }
}
