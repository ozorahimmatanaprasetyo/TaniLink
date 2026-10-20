<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CommodityListing;
use App\Models\PreOrder;
use App\Models\PlantingProgress;
use App\Models\DeliveryTracking;

class TaniLinkController extends Controller
{
    // ==========================================
    // AUTHENTICATION & ONBOARDING
    // ==========================================

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'pending', // will be set in onboarding
        ]);

        Auth::login($user);

        return redirect()->route('onboarding')->with('success', 'Registrasi berhasil! Silakan pilih peran Anda.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            // Jika admin, langsung ke dashboard admin
            if ($user->role === 'admin') {
                session(['active_role' => 'admin']);
                return redirect()->route('portal.admin')->with('success', 'Selamat datang, Admin!');
            }

            // Jika user sudah punya role, langsung ke portal
            if (in_array($user->role, ['petani', 'pembeli'])) {
                session(['active_role' => $user->role]);
                return redirect()->route('portal.' . $user->role)->with('success', 'Selamat datang, ' . $user->name . '!');
            }

            // Jika user baru (pending), lempar ke onboarding
            return redirect()->route('onboarding');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil keluar dari akun.');
    }

    public function onboarding()
    {
        if (Auth::user()->role === 'admin') {
            return redirect()->route('portal.admin');
        }
        return view('auth.onboarding');
    }

    public function setOnboarding(Request $request)
    {
        $request->validate(['purpose' => 'required|in:petani,pembeli']);
        
        $user = Auth::user();
        $user->role = $request->purpose;
        $user->save();

        session(['active_role' => $request->purpose]);

        return redirect()->route('portal.' . $request->purpose)
            ->with('success', 'Personalisasi berhasil! Selamat datang di TaniLink.');
    }

    public function index()
    {
        $role = session('active_role', Auth::user()->role);
        
        if ($role === 'admin') return redirect()->route('portal.admin');
        if (in_array($role, ['petani', 'pembeli'])) return redirect()->route('portal.' . $role);
        
        return redirect()->route('onboarding');
    }

    // ==========================================
    // 1. PORTAL PETANI (GAPOKTAN)
    // ==========================================

    public function petani(Request $request)
    {
        if (Auth::user()->role === 'pending') {
            return redirect()->route('onboarding');
        }

        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $myListings = CommodityListing::where('user_id', $petani->id)->latest()->get();

        $activeOrders = PreOrder::with(['commodityListing', 'buyer', 'plantingProgress', 'deliveryTracking'])
            ->where('farmer_id', $petani->id)
            ->latest()
            ->get();

        $dpReceived = $activeOrders->sum('dp_amount');
        $escrowLocked = $activeOrders->sum('escrow_locked_amount');
        $totalCropKg = $myListings->sum('estimated_harvest_kg');

        return view('tanilink.petani', compact('petani', 'myListings', 'activeOrders', 'dpReceived', 'escrowLocked', 'totalCropKg'));
    }

    public function petaniListings(Request $request)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $myListings = CommodityListing::where('user_id', $petani->id)->latest()->get();
        $activeOrders = PreOrder::where('farmer_id', $petani->id)->get();

        $dpReceived = $activeOrders->sum('dp_amount');
        $totalCropKg = $myListings->sum('estimated_harvest_kg');

        return view('tanilink.farmer.listings', compact('petani', 'myListings', 'dpReceived', 'totalCropKg'));
    }

    public function petaniCreateListing(Request $request)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        return view('tanilink.farmer.create', compact('petani'));
    }

    public function petaniOrders(Request $request)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $activeOrders = PreOrder::with(['commodityListing', 'buyer', 'plantingProgress', 'deliveryTracking'])
            ->where('farmer_id', $petani->id)
            ->latest()
            ->get();

        $totalKg = $activeOrders->sum('quantity_kg');
        $totalContract = $activeOrders->sum('total_amount');
        $dpReceived = $activeOrders->sum('dp_amount');

        return view('tanilink.farmer.orders', compact('petani', 'activeOrders', 'totalKg', 'totalContract', 'dpReceived'));
    }

    public function petaniOrderProgress($id)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $order = PreOrder::with(['commodityListing', 'buyer', 'plantingProgress', 'deliveryTracking'])
            ->where('farmer_id', $petani->id)
            ->findOrFail($id);

        return view('tanilink.farmer.progress', compact('petani', 'order'));
    }

    public function petaniProfile()
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $listingCount = CommodityListing::where('user_id', $petani->id)->count();

        return view('tanilink.farmer.profile', compact('petani', 'listingCount'));
    }

    public function updatePetaniProfile(Request $request)
    {
        $petani = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        $petani->update([
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $petani->organization,
            'phone' => $validated['phone'] ?? $petani->phone,
            'location' => $validated['location'] ?? $petani->location,
        ]);

        return back()->with('success', 'Profil kelompok tani berhasil diperbarui!');
    }

    public function storeListing(Request $request)
    {
        $petani = Auth::user();

        $validated = $request->validate([
            'commodity_name' => 'required|string|max:255',
            'category' => 'required|string',
            'variety' => 'nullable|string|max:100',
            'land_area_m2' => 'required|numeric|min:1',
            'estimated_harvest_kg' => 'required|numeric|min:1',
            'price_per_kg' => 'required|numeric|min:100',
            'harvest_estimated_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $imageUrl = 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('listings', 'public');
            $imageUrl = '/storage/' . $path;
        }

        CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => $validated['commodity_name'],
            'category' => $validated['category'],
            'variety' => $validated['variety'] ?? 'Unggul Lokal',
            'land_area_m2' => $validated['land_area_m2'],
            'estimated_harvest_kg' => $validated['estimated_harvest_kg'],
            'available_kg' => $validated['estimated_harvest_kg'],
            'price_per_kg' => $validated['price_per_kg'],
            'harvest_estimated_date' => $validated['harvest_estimated_date'],
            'location' => $petani->location ?? 'Malang, Jawa Timur',
            'image_url' => $imageUrl,
            'description' => $validated['description'] ?? 'Lahan produktif siap tanam.',
            'status' => 'available',
        ]);

        return redirect()->route('petani.komoditas')->with('success', 'Jualan komoditas berhasil diterbitkan!');
    }

    public function storeProgress(Request $request)
    {
        $validated = $request->validate([
            'pre_order_id' => 'required|exists:pre_orders,id',
            'stage_name' => 'required|string',
            'note' => 'required|string',
        ]);

        $preOrder = PreOrder::findOrFail($validated['pre_order_id']);

        PlantingProgress::create([
            'pre_order_id' => $preOrder->id,
            'stage_name' => $validated['stage_name'],
            'note' => $validated['note'],
            'photo_url' => 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80',
            'location_tag' => 'Lahan Petani',
            'reported_at' => now(),
            'is_verified' => true,
        ]);

        $preOrder->update([
            'current_stage' => $validated['stage_name'],
            'stage_percentage' => min(100, $preOrder->stage_percentage + 15),
        ]);

        return back()->with('success', 'Update progres berhasil dikirim ke pembeli!');
    }

    // ==========================================
    // 2. PORTAL PEMBELI (HOREKA)
    // ==========================================

    public function pembeli(Request $request)
    {
        if (Auth::user()->role === 'pending') {
            return redirect()->route('onboarding');
        }

        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $catalog = CommodityListing::with('farmer')
            ->where('status', '!=', 'draft')
            ->latest()
            ->get();

        $myOrders = PreOrder::with(['commodityListing', 'farmer', 'plantingProgress', 'deliveryTracking'])
            ->where('buyer_id', $pembeli->id)
            ->latest()
            ->get();

        $totalPreOrderValue = $myOrders->sum('total_amount');
        $escrowHeld = $myOrders->sum('escrow_locked_amount');

        return view('tanilink.pembeli', compact('pembeli', 'catalog', 'myOrders', 'totalPreOrderValue', 'escrowHeld'));
    }

    public function pembeliCatalog(Request $request)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $catalog = CommodityListing::with('farmer')
            ->where('status', '!=', 'draft')
            ->latest()
            ->get();

        return view('tanilink.buyer.catalog', compact('pembeli', 'catalog'));
    }

    public function pembeliOrders(Request $request)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $myOrders = PreOrder::with(['commodityListing', 'farmer', 'plantingProgress', 'deliveryTracking'])
            ->where('buyer_id', $pembeli->id)
            ->latest()
            ->get();

        $totalPreOrderValue = $myOrders->sum('total_amount');
        $escrowHeld = $myOrders->sum('escrow_locked_amount');

        return view('tanilink.buyer.orders', compact('pembeli', 'myOrders', 'totalPreOrderValue', 'escrowHeld'));
    }

    public function pembeliTracking($id)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $order = PreOrder::with(['commodityListing', 'farmer', 'plantingProgress', 'deliveryTracking'])
            ->where('buyer_id', $pembeli->id)
            ->findOrFail($id);

        return view('tanilink.buyer.tracking', compact('pembeli', 'order'));
    }

    public function pembeliCommodityDetail($id)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $listing = CommodityListing::with('farmer')->findOrFail($id);

        return view('tanilink.buyer.product', compact('pembeli', 'listing'));
    }

    public function pembeliPreorderForm($id)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $listing = CommodityListing::with('farmer')->findOrFail($id);

        return view('tanilink.buyer.preorder', compact('pembeli', 'listing'));
    }

    public function storeBooking(Request $request)
    {
        $pembeli = Auth::user();

        if ($pembeli->role !== 'pembeli') {
            return back()->withErrors(['error' => 'Hanya pembeli yang bisa melakukan booking.']);
        }

        $validated = $request->validate([
            'listing_id' => 'required|exists:commodity_listings,id',
            'quantity_kg' => 'required|numeric|min:1',
        ]);

        $listing = CommodityListing::findOrFail($validated['listing_id']);
        $qty = (int) $validated['quantity_kg'];

        if ($listing->status !== 'available') {
            return back()->withErrors(['error' => 'Komoditas ini sudah tidak tersedia.'])->withInput();
        }
        if ($qty > $listing->available_kg) {
            return back()->withErrors(['error' => "Stok tidak mencukupi. Tersedia: {$listing->available_kg} kg."])->withInput();
        }

        $total = $qty * $listing->price_per_kg;
        $dp = $total * 0.3;
        $escrowLocked = $total * 0.7;

        $order = PreOrder::create([
            'order_code' => 'TL-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4)),
            'commodity_listing_id' => $listing->id,
            'farmer_id' => $listing->user_id,
            'buyer_id' => $pembeli->id,
            'quantity_kg' => $qty,
            'price_per_kg' => $listing->price_per_kg,
            'total_amount' => $total,
            'dp_amount' => $dp,
            'escrow_locked_amount' => $escrowLocked,
            'status' => 'dp_cair',
            'current_stage' => 'Fase Benih & Penyemaian',
            'stage_percentage' => 20,
        ]);

        PlantingProgress::create([
            'pre_order_id' => $order->id,
            'stage_name' => 'Fase Benih & Penyemaian',
            'note' => 'Kontrak disepakati. DP 30% diteruskan ke petani sebagai modal benih awal.',
            'photo_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&w=600&q=80',
            'location_tag' => $listing->location,
            'reported_at' => now(),
            'is_verified' => true,
        ]);

        DeliveryTracking::create([
            'pre_order_id' => $order->id,
            'pickup_address' => 'Lahan Petani, ' . $listing->location,
            'destination_address' => ($pembeli->organization ?? 'Grand Hotel Malang') . ', ' . ($pembeli->location ?? 'Kota Malang'),
            'distance_km' => 18.0,
            'estimated_time' => '45 Menit',
            'status' => 'scheduled',
            'pickup_time' => now()->addDays(30)->setTime(6, 30),
            'driver_name' => 'Budi Santoso (Pickup L300)',
            'driver_vehicle' => 'Pickup L300',
            'cargo_crates' => max(1, ceil($qty / 20)),
        ]);

        $listing->decrement('available_kg', min($listing->available_kg, $qty));
        if ($listing->available_kg <= 0) {
            $listing->update(['status' => 'booked']);
        }

        return redirect()->route('pembeli.pesanan')->with('success', 'Pre-Order berhasil! DP diamankan di Escrow.');
    }

    // ==========================================
    // 3. PORTAL ADMIN (TaniLink HQ)
    // ==========================================

    public function admin()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'buyer', 'deliveryTracking'])
            ->latest()
            ->get();

        $allListings = CommodityListing::with('farmer')->latest()->get();
        $allUsers = User::all();

        $totalEscrowLocked = $allOrders->sum('escrow_locked_amount');
        $totalDpDisbursed = $allOrders->sum('dp_amount');
        $totalGmv = $allOrders->sum('total_amount');

        return view('tanilink.admin', compact('admin', 'allOrders', 'allListings', 'allUsers', 'totalEscrowLocked', 'totalDpDisbursed', 'totalGmv'));
    }

    public function adminUsers()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        $allUsers = User::latest()->get();

        return view('tanilink.admin.users', compact('admin', 'allUsers'));
    }

    public function adminListings()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        $allListings = CommodityListing::with('farmer')->latest()->get();

        return view('tanilink.admin.listings', compact('admin', 'allListings'));
    }

    public function adminOrders()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'buyer', 'deliveryTracking'])
            ->latest()
            ->get();

        $totalEscrowLocked = $allOrders->sum('escrow_locked_amount');
        $totalDpDisbursed = $allOrders->sum('dp_amount');
        $totalGmv = $allOrders->sum('total_amount');

        return view('tanilink.admin.orders', compact('admin', 'allOrders', 'totalEscrowLocked', 'totalDpDisbursed', 'totalGmv'));
    }

    public function adminReports()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'buyer'])
            ->latest()
            ->get();

        $allUsers = User::all();
        $allListings = CommodityListing::all();

        $totalGmv = $allOrders->sum('total_amount');
        $totalDpDisbursed = $allOrders->sum('dp_amount');
        $totalEscrowLocked = $allOrders->sum('escrow_locked_amount');

        return view('tanilink.admin.reports', compact('admin', 'allOrders', 'allUsers', 'allListings', 'totalGmv', 'totalDpDisbursed', 'totalEscrowLocked'));
    }

    public function releaseEscrow($orderId)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $order = PreOrder::findOrFail($orderId);
        $order->update([
            'status' => 'selesai',
            'escrow_locked_amount' => 0,
        ]);

        if ($order->deliveryTracking) {
            $order->deliveryTracking->update(['status' => 'delivered']);
        }

        return back()->with('success', 'Dana escrow berhasil dirilis penuh ke petani!');
    }

    // ==========================================
    // 4. COMMON (PENGATURAN & BANTUAN)
    // ==========================================

    public function settings()
    {
        $user = Auth::user();
        return view('tanilink.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $user->organization,
            'phone' => $validated['phone'] ?? $user->phone,
            'location' => $validated['location'] ?? $user->location,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return back()->with('success', 'Pengaturan profil akun berhasil diperbarui!');
    }

    public function helpCenter()
    {
        return view('tanilink.help');
    }
}
