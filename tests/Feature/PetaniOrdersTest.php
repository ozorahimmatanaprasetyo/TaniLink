<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\PreOrder;
use App\Models\HarvestBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PetaniOrdersTest extends TestCase
{
    use RefreshDatabase;

    public function test_petani_can_see_active_orders_on_dashboard_and_orders_page()
    {
        $farmer = User::factory()->create([
            'role' => 'petani',
            'name' => 'Pak Joko Widodo Subroto',
            'email' => 'petani.joko@example.test',
        ]);

        $buyer = User::factory()->create([
            'role' => 'pembeli',
            'name' => 'Restoran Nusantara',
        ]);

        $listing = CommodityListing::create([
            'user_id' => $farmer->id,
            'commodity_name' => 'Cabai Rawit Merah Ori 212',
            'category' => 'Cabai & Bumbu',
            'variety' => 'Ori 212',
            'location' => 'Batu, Jawa Timur',
            'land_area_m2' => 2000,
            'price_per_kg' => 45000,
            'estimated_harvest_kg' => 1000,
            'available_kg' => 900,
            'harvest_estimated_date' => now()->addDays(30),
            'status' => 'available',
        ]);

        $order = PreOrder::create([
            'order_code' => 'PO-DEMO-TEST1',
            'farmer_id' => $farmer->id,
            'buyer_id' => $buyer->id,
            'commodity_listing_id' => $listing->id,
            'quantity_kg' => 100,
            'price_per_kg' => 45000,
            'total_amount' => 4500000,
            'dp_amount' => 1350000,
            'escrow_locked_amount' => 3150000,
            'status' => 'menunggu_konfirmasi',
            'need_date' => now()->addDays(30),
            'current_stage' => 'Pengajuan Pre-Order',
            'stage_percentage' => 5,
        ]);

        // 1. Check dashboard /petani
        $responseDashboard = $this->actingAs($farmer)->get(route('portal.petani'));
        $responseDashboard->assertStatus(200);
        $responseDashboard->assertSee('Pesanan Terbaru');
        $responseDashboard->assertDontSee('Belum Ada Pesanan');
        $responseDashboard->assertSee('PO-DEMO-TEST1');
        $responseDashboard->assertSee('Cabai Rawit Merah Ori 212');
        $responseDashboard->assertSee('Restoran Nusantara');

        // 2. Check /petani/pesanan
        $responseOrders = $this->actingAs($farmer)->get(route('petani.pesanan'));
        $responseOrders->assertStatus(200);
        $responseOrders->assertSee('Daftar Pesanan Aktif');
        $responseOrders->assertDontSee('Tidak Ada Pesanan Aktif');
        $responseOrders->assertSee('PO-DEMO-TEST1');
        $responseOrders->assertSee('Cabai Rawit Merah Ori 212');
        $responseOrders->assertSee('Restoran Nusantara');

        // 3. Check order detail page /petani/pesanan/{id}
        $responseDetail = $this->actingAs($farmer)->get(route('petani.pesanan.show', $order->id));
        $responseDetail->assertStatus(200);
        $responseDetail->assertSee('PO-DEMO-TEST1');
        $responseDetail->assertSee('Cabai Rawit Merah Ori 212');

        // 4. Other farmer must NOT see this order
        $otherFarmer = User::factory()->create([
            'role' => 'petani',
            'name' => 'Petani Lain',
        ]);
        $responseOther = $this->actingAs($otherFarmer)->get(route('petani.pesanan'));
        $responseOther->assertStatus(200);
        $responseOther->assertDontSee('PO-DEMO-TEST1');
        $responseOther->assertSee('Tidak Ada Pesanan Aktif');
    }
}

