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
use App\Models\HelpTopic;
use App\Models\SystemSetting;

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
            'phone' => 'nullable|string|max:25',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'pending',
            'location' => null,
            'organization' => null,
        ]);

        Auth::login($user);

        return redirect()->route('onboarding')->with('success', 'Registrasi berhasil! Silakan pilih peran Anda.');
    }

    public function login(Request $request)
    {
        // Support login by email OR phone number
        $loginInput = $request->input('login', $request->input('email'));
        
        $request->validate([
            'password' => 'required',
        ]);

        if (empty($loginInput)) {
            return back()->withErrors([
                'login' => 'Email atau Nomor HP wajib diisi.',
            ]);
        }

        $user = User::where('email', $loginInput)
            ->orWhere('phone', $loginInput)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'login' => 'Akun dengan email atau nomor HP ini belum terdaftar. Silakan registrasi terlebih dahulu.',
            ])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah. Silakan periksa kembali.',
            ])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();
        
        if ($user->role === 'admin') {
            session(['active_role' => 'admin']);
            return redirect()->route('portal.admin')->with('success', 'Selamat datang, Admin!');
        }

        if (in_array($user->role, ['petani', 'pembeli'])) {
            session(['active_role' => $user->role]);
            return redirect()->route('portal.' . $user->role)->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        return redirect()->route('onboarding');
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

        // Hanya boleh memilih role saat masih 'pending'
        if ($user->role !== 'pending') {
            session(['active_role' => $user->role]);
            return redirect()->route('portal.' . $user->role)
                ->with('info', 'Peran Anda sudah terdaftar sebagai ' . ucfirst($user->role) . ' dan tidak dapat diubah.');
        }

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
    // 1. PORTAL PETANI
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

    public function storeListing(Request $request)
    {
        $petani = Auth::user();

        $validated = $request->validate([
            'commodity_name' => 'required|string|max:255',
            'category' => 'required|string',
            'variety' => 'nullable|string|max:100',
            'grade' => 'nullable|string|max:50',
            'land_area_m2' => 'required|numeric|min:1',
            'estimated_harvest_kg' => 'required|numeric|min:1',
            'available_kg' => 'nullable|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:100',
            'harvest_estimated_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        $defaultImages = [
            'Cabai & Bumbu' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80',
            'Sayuran' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
            'Palawija' => 'https://images.unsplash.com/photo-1551754655-cd27e38d2076?auto=format&fit=crop&w=600&q=80',
            'Buah' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?auto=format&fit=crop&w=600&q=80',
            'Biji-bijian' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=600&q=80',
        ];
        $imageUrl = $defaultImages[$validated['category']] ?? 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80';
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('listings', 'public');
            $imageUrl = '/storage/' . $path;
        }

        $avail = $request->filled('available_kg') ? min((float)$validated['available_kg'], (float)$validated['estimated_harvest_kg']) : (float)$validated['estimated_harvest_kg'];

        CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => $validated['commodity_name'],
            'category' => $validated['category'],
            'variety' => $validated['variety'] ?? 'Unggul Lokal',
            'grade' => $validated['grade'] ?? 'Grade A',
            'land_area_m2' => $validated['land_area_m2'],
            'estimated_harvest_kg' => $validated['estimated_harvest_kg'],
            'available_kg' => $avail,
            'price_per_kg' => $validated['price_per_kg'],
            'harvest_estimated_date' => $validated['harvest_estimated_date'],
            'location' => $petani->location ?? 'Malang, Jawa Timur',
            'latitude' => $petani->latitude,
            'longitude' => $petani->longitude,
            'image_url' => $imageUrl,
            'description' => $validated['description'] ?? 'Lahan produktif siap tanam.',
            'status' => 'available',
        ]);

        return redirect()->route('petani.komoditas')->with('success', 'Komoditas berhasil diterbitkan!');
    }

    public function petaniEditListing($id)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);

        return view('tanilink.farmer.edit', compact('petani', 'listing'));
    }

    public function petaniUpdateListing(Request $request, $id)
    {
        $petani = Auth::user();

        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);

        $validated = $request->validate([
            'commodity_name' => 'required|string|max:255',
            'category' => 'required|string',
            'variety' => 'nullable|string|max:100',
            'grade' => 'nullable|string|max:50',
            'land_area_m2' => 'required|numeric|min:1',
            'estimated_harvest_kg' => 'required|numeric|min:1',
            'available_kg' => 'nullable|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:100',
            'harvest_estimated_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:available,booked',
        ]);

        $data = [
            'commodity_name' => $validated['commodity_name'],
            'category' => $validated['category'],
            'variety' => $validated['variety'] ?? $listing->variety,
            'grade' => $validated['grade'] ?? ($listing->grade ?? 'Grade A'),
            'land_area_m2' => $validated['land_area_m2'],
            'estimated_harvest_kg' => $validated['estimated_harvest_kg'],
            'available_kg' => $request->filled('available_kg') ? min((float)$validated['available_kg'], (float)$validated['estimated_harvest_kg']) : $listing->available_kg,
            'price_per_kg' => $validated['price_per_kg'],
            'harvest_estimated_date' => $validated['harvest_estimated_date'],
            'description' => $validated['description'],
            'status' => $request->filled('status') ? $validated['status'] : $listing->status,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('listings', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $listing->update($data);

        return redirect()->route('petani.komoditas')->with('success', 'Data komoditas berhasil diperbarui!');
    }

    public function petaniDestroyListing($id)
    {
        $petani = Auth::user();
        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);

        // Jangan hapus jika ada pesanan aktif
        if ($listing->preOrders()->count() > 0) {
            return back()->with('error', 'Komoditas tidak dapat dihapus karena sudah memiliki kontrak pesanan.');
        }

        $listing->delete();

        return redirect()->route('petani.komoditas')->with('success', 'Komoditas berhasil dihapus!');
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

    public function storeProgress(Request $request)
    {
        $petani = Auth::user();

        $validated = $request->validate([
            'pre_order_id' => 'required|exists:pre_orders,id',
            'stage_name' => 'required|string',
            'stage_status' => 'required|in:berlangsung,selesai',
            'note' => 'required|string',
            'photo' => 'nullable|image|max:3072',
        ]);

        $preOrder = PreOrder::where('farmer_id', $petani->id)->findOrFail($validated['pre_order_id']);

        if (!in_array($preOrder->status, ['dikonfirmasi', 'dp_cair', 'tanam_berjalan', 'siap_panen'])) {
            return back()->withErrors(['error' => 'Pesanan harus dikonfirmasi terlebih dahulu sebelum progres dapat diperbarui.']);
        }

        // Integritas Kontrak: Validasi Penguncian Fase Lampau yang sudah 'selesai'
        $stageOrder = [
            'Fase Benih & Penyemaian' => 1,
            'Fase Vegetatif & Pertunasan' => 2,
            'Fase Pembungaan & Bakal Buah' => 3,
            'Fase Pematangan & Siap Panen' => 4,
        ];

        $currentIdx = $stageOrder[$preOrder->current_stage] ?? 1;
        $targetIdx = $stageOrder[$validated['stage_name']] ?? 1;

        if ($targetIdx < $currentIdx) {
            return back()->withErrors(['stage_name' => 'Fase sebelumnya telah selesai dan terkunci. Anda tidak dapat mengubah kembali fase yang sudah lampau.']);
        }

        if ($targetIdx === $currentIdx && $preOrder->current_stage_status === 'selesai' && $validated['stage_status'] !== 'selesai') {
            return back()->withErrors(['stage_status' => 'Fase ini sudah ditandai selesai dan tidak dapat dikembalikan ke status berlangsung. Silakan pilih fase berikutnya.']);
        }

        $photoUrl = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('progress', 'public');
            $photoUrl = '/storage/' . $path;
        } else {
            // Default high-quality farm milestone photos
            $defaultPhasePhotos = [
                'Fase Benih & Penyemaian' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&w=600&q=80',
                'Fase Vegetatif & Pertunasan' => 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80',
                'Fase Pembungaan & Bakal Buah' => 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80',
                'Fase Pematangan & Siap Panen' => 'https://images.unsplash.com/photo-1588252303782-cb80119abd6d?auto=format&fit=crop&w=600&q=80',
            ];
            $photoUrl = $defaultPhasePhotos[$validated['stage_name']] ?? 'https://images.unsplash.com/photo-1592417817098-8f3d6910985b?auto=format&fit=crop&w=600&q=80';
        }

        $stageNames = [
            1 => 'Fase Benih & Penyemaian',
            2 => 'Fase Vegetatif & Pertunasan',
            3 => 'Fase Pembungaan & Bakal Buah',
            4 => 'Fase Pematangan & Siap Panen',
        ];

        // Hitung persentase dinamis sesuai fase dan status (berlangsung / selesai)
        $pctMap = [
            'Fase Benih & Penyemaian' => ['berlangsung' => 15, 'selesai' => 25],
            'Fase Vegetatif & Pertunasan' => ['berlangsung' => 45, 'selesai' => 50],
            'Fase Pembungaan & Bakal Buah' => ['berlangsung' => 70, 'selesai' => 75],
            'Fase Pematangan & Siap Panen' => ['berlangsung' => 90, 'selesai' => 100],
        ];

        PlantingProgress::create([
            'pre_order_id' => $preOrder->id,
            'stage_name' => $validated['stage_name'],
            'stage_status' => $validated['stage_status'],
            'note' => $validated['note'],
            'photo_url' => $photoUrl,
            'location_tag' => $preOrder->commodityListing->location ?? 'Lahan Petani',
            'reported_at' => now(),
            'is_verified' => false,
        ]);

        // Jika fase ditandai selesai dan belum fase ke-4, fase berikutnya otomatis aktif berstatus 'berlangsung'
        if ($validated['stage_status'] === 'selesai' && $targetIdx < 4) {
            $nextIdx = $targetIdx + 1;
            $nextStageName = $stageNames[$nextIdx];
            $nextInitialPct = [
                2 => 35,
                3 => 65,
                4 => 85,
            ];

            $orderUpdate = [
                'current_stage' => $nextStageName,
                'current_stage_status' => 'berlangsung',
                'stage_percentage' => $nextInitialPct[$nextIdx] ?? 50,
            ];

            $preOrder->update($orderUpdate);

            return back()->with('success', $validated['stage_name'] . ' telah ditandai Selesai. ' . $nextStageName . ' otomatis aktif dengan status Sedang Berlangsung!');
        } else {
            $newPercentage = $pctMap[$validated['stage_name']][$validated['stage_status']] ?? min(100, $preOrder->stage_percentage + 15);

            $orderUpdate = [
                'current_stage' => $validated['stage_name'],
                'current_stage_status' => $validated['stage_status'],
                'stage_percentage' => $newPercentage,
            ];

            if ($validated['stage_name'] === 'Fase Pematangan & Siap Panen' && $validated['stage_status'] === 'selesai') {
                $orderUpdate['status'] = 'siap_panen';
                $orderUpdate['stage_percentage'] = 100;
            }

            $preOrder->update($orderUpdate);

            $statusLabel = $validated['stage_status'] === 'selesai' ? 'Selesai (Siap Panen)' : 'Sedang Berlangsung';
            return back()->with('success', 'Update progres ' . $validated['stage_name'] . ' (' . $statusLabel . ') berhasil disimpan dan dikirim ke konsumen!');
        }
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
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $petani->organization,
            'phone' => $validated['phone'] ?? $petani->phone,
            'location' => $validated['location'] ?? $petani->location,
            'latitude' => $validated['latitude'] ?? $petani->latitude,
            'longitude' => $validated['longitude'] ?? $petani->longitude,
            'bank_name' => $validated['bank_name'] ?? $petani->bank_name,
            'bank_account' => $validated['bank_account'] ?? $petani->bank_account,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $petani->update($data);

        return back()->with('success', 'Profil petani berhasil diperbarui!');
    }

    // ==========================================
    // 2. PORTAL KONSUMEN
    // ==========================================

    public function pembeli(Request $request)
    {
        if (Auth::user()->role === 'pending') {
            return redirect()->route('onboarding');
        }

        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        // Hanya komoditas yang tersedia untuk dipesan konsumen
        $catalog = CommodityListing::with('farmer')
            ->where('status', 'available')
            ->where('available_kg', '>', 0)
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

        $query = CommodityListing::with('farmer')
            ->where('status', 'available')
            ->where('available_kg', '>', 0);

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_kg', '<=', (float)$request->max_price);
        }

        if ($request->filled('harvest_month')) {
            $query->whereRaw("DATE_FORMAT(harvest_estimated_date, '%Y-%m') = ?", [$request->harvest_month]);
        }

        $catalog = $query->latest()->get();

        $locations = CommodityListing::where('status', 'available')
            ->where('available_kg', '>', 0)
            ->pluck('location')
            ->unique()
            ->filter();

        return view('tanilink.buyer.catalog', compact('pembeli', 'catalog', 'locations'));
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
            return back()->withErrors(['error' => 'Hanya konsumen yang bisa melakukan booking.']);
        }

        $validated = $request->validate([
            'listing_id' => 'required|exists:commodity_listings,id',
            'quantity_kg' => 'required|numeric|min:0.1',
            'need_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:1000',
        ]);

        $qty = (float) $validated['quantity_kg'];

        return \Illuminate\Support\Facades\DB::transaction(function () use ($validated, $qty, $pembeli) {
            $listing = CommodityListing::where('id', $validated['listing_id'])->lockForUpdate()->firstOrFail();

            if ($listing->status !== 'available' || $listing->available_kg <= 0) {
                return back()->withErrors(['error' => 'Komoditas ini sudah tidak tersedia.'])->withInput();
            }
            if ($qty > $listing->available_kg) {
                return back()->withErrors(['error' => "Stok alokasi tidak mencukupi. Tersisa: {$listing->available_kg} kg."])->withInput();
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
                'need_date' => $validated['need_date'] ?? $listing->harvest_estimated_date,
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $total,
                'dp_amount' => $dp,
                'escrow_locked_amount' => $escrowLocked,
                'status' => 'menunggu_konfirmasi',
                'current_stage' => 'Menunggu Konfirmasi Petani',
                'stage_percentage' => 10,
            ]);

            DeliveryTracking::create([
                'pre_order_id' => $order->id,
                'pickup_address' => 'Lahan Petani, ' . $listing->location,
                'destination_address' => ($pembeli->organization ?? 'Lokasi Konsumen') . ', ' . ($pembeli->location ?? 'Alamat Pemesan'),
                // Jadwal pickup dikosongkan — akan disepakati petani & pembeli setelah konfirmasi
                'status' => 'pending',
                'driver_name' => 'Belum ditentukan — koordinasi mandiri petani & konsumen',
                'driver_vehicle' => 'Belum ditentukan',
                'cargo_crates' => max(1, ceil($qty / 20)),
            ]);

            $listing->decrement('available_kg', $qty);
            if ($listing->available_kg <= 0) {
                $listing->update(['status' => 'booked']);
            }

            return redirect()->route('pembeli.pesanan')->with('success', 'Pengajuan pre-order berhasil dikirim! Status saat ini Menunggu Konfirmasi Petani.');
        });
    }

    public function petaniConfirmOrder($id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pesanan ini sudah dikonfirmasi atau diproses sebelumnya.');
        }

        $order->update([
            'status' => 'dikonfirmasi',
            'confirmed_at' => now(),
            'current_stage' => 'Fase Benih & Penyemaian',
            'current_stage_status' => 'berlangsung',
            'stage_percentage' => 20,
        ]);

        PlantingProgress::create([
            'pre_order_id' => $order->id,
            'stage_name' => 'Fase Benih & Penyemaian',
            'stage_status' => 'berlangsung',
            'note' => 'Pesanan telah diterima dan dikonfirmasi petani. Rencana alokasi lahan dan benih dipersiapkan.',
            'photo_url' => 'https://images.unsplash.com/photo-1530836369250-ef72a3f5cda8?auto=format&fit=crop&w=600&q=80',
            'location_tag' => $order->commodityListing->location ?? 'Lahan Petani',
            'reported_at' => now(),
            'is_verified' => false,
        ]);

        return back()->with('success', 'Pesanan ' . $order->order_code . ' berhasil diterima dan masuk ke tahap budidaya!');
    }

    public function petaniRejectOrder(Request $request, $id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pesanan ini tidak dapat ditolak karena sudah dalam proses.');
        }

        $reason = $request->input('reason', 'Kapasitas lahan atau jadwal panen belum memungkinkan.');

        \Illuminate\Support\Facades\DB::transaction(function () use ($order, $reason) {
            $order->update([
                'status' => 'dibatalkan',
                'rejection_reason' => $reason,
                'current_stage' => 'Ditolak Petani',
                'current_stage_status' => 'selesai',
            ]);

            // Kembalikan stok alokasi komoditas
            if ($order->commodityListing) {
                $order->commodityListing->increment('available_kg', $order->quantity_kg);
                if ($order->commodityListing->status === 'booked' && $order->commodityListing->available_kg > 0) {
                    $order->commodityListing->update(['status' => 'available']);
                }
            }
        });

        return back()->with('success', 'Pesanan ' . $order->order_code . ' telah ditolak dan stok telah dikembalikan ke katalog.');
    }

    public function pembeliCompleteOrder($id)
    {
        $pembeli = Auth::user();
        $order = PreOrder::where('buyer_id', $pembeli->id)->findOrFail($id);

        if ($order->status === 'selesai') {
            return back()->with('info', 'Pesanan ini sudah selesai.');
        }

        // Pembeli hanya bisa konfirmasi penerimaan jika hasil panen sudah siap atau dalam pengiriman
        if (!in_array($order->status, ['siap_panen', 'dalam_pengiriman'])) {
            return back()->with('error', 'Konfirmasi penerimaan hanya dapat dilakukan setelah proses panen selesai dan hasil siap diserahkan.');
        }

        $order->update([
            'status' => 'selesai',
            'completed_at' => now(),
            'current_stage' => 'Fase Pematangan & Siap Panen',
            'current_stage_status' => 'selesai',
            'stage_percentage' => 100,
        ]);

        if ($order->deliveryTracking) {
            $order->deliveryTracking->update(['status' => 'delivered']);
        }

        return back()->with('success', 'Terima kasih! Anda telah mengonfirmasi penerimaan hasil panen. Transaksi pre-order selesai.');
    }

    public function pembeliProfile()
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $myOrdersCount = PreOrder::where('buyer_id', $pembeli->id)->count();

        return view('tanilink.buyer.profile', compact('pembeli', 'myOrdersCount'));
    }

    public function updatePembeliProfile(Request $request)
    {
        $pembeli = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $pembeli->organization,
            'phone' => $validated['phone'] ?? $pembeli->phone,
            'location' => $validated['location'] ?? $pembeli->location,
            'latitude' => $validated['latitude'] ?? $pembeli->latitude,
            'longitude' => $validated['longitude'] ?? $pembeli->longitude,
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $pembeli->update($data);

        return back()->with('success', 'Profil konsumen berhasil diperbarui!');
    }

    // ==========================================
    // 3. PORTAL ADMIN
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
        // Kecualikan peran admin dari kelola pengguna
        $allUsers = User::where('role', '!=', 'admin')->latest()->get();

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

        // Hanya tampilkan Petani dan Konsumen (Admin dikecualikan)
        $allUsers = User::where('role', '!=', 'admin')->latest()->get();

        return view('tanilink.admin.users', compact('admin', 'allUsers'));
    }

    public function adminDestroyUser($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $user = User::where('role', '!=', 'admin')->findOrFail($id);
        $user->delete();

        return back()->with('success', 'Akun pengguna berhasil dihapus!');
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

    public function adminDestroyListing($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $listing = CommodityListing::findOrFail($id);
        $listing->delete();

        return back()->with('success', 'Listing komoditas berhasil dihapus oleh admin!');
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

        $allUsers = User::where('role', '!=', 'admin')->get();
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

        // Escrow hanya dapat dicairkan jika pesanan sudah selesai (dikonfirmasi pembeli)
        if ($order->status !== 'selesai') {
            return back()->with('error', 'Escrow hanya dapat dicairkan setelah pembeli mengonfirmasi penerimaan (status: Selesai).');
        }

        // Simulasi pencairan: zeroing escrow, status tetap 'selesai'
        $order->update([
            'escrow_locked_amount' => 0,
        ]);

        return back()->with('success', '[SIMULASI] Dana escrow 70% berhasil dirilis ke petani. Proses ini adalah simulasi dalam sistem TaniLink.');
    }

    public function adminHelp()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        // Pertanyaan yang dimasukkan pertama menjadi paling bawah (order by id DESC)
        $petaniTopics = HelpTopic::where('target_role', 'petani')->orderBy('id', 'desc')->get();
        $konsumenTopics = HelpTopic::whereIn('target_role', ['konsumen', 'pembeli'])->orderBy('id', 'desc')->get();
        $semuaTopics = HelpTopic::where('target_role', 'semua')->orderBy('id', 'desc')->get();

        $adminPhone = SystemSetting::get('admin_phone', '+62 812-3456-7890');
        $adminEmail = SystemSetting::get('admin_email', 'support@tanilink.id');
        $adminHours = SystemSetting::get('admin_hours', 'Senin - Sabtu (08.00 - 17.00 WIB)');

        return view('tanilink.admin.help', compact('petaniTopics', 'konsumenTopics', 'semuaTopics', 'adminPhone', 'adminEmail', 'adminHours'));
    }

    public function updateAdminContact(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'admin_phone' => 'required|string|max:50',
            'admin_email' => 'required|string|max:100',
            'admin_hours' => 'required|string|max:100',
        ]);

        SystemSetting::set('admin_phone', $validated['admin_phone']);
        SystemSetting::set('admin_email', $validated['admin_email']);
        SystemSetting::set('admin_hours', $validated['admin_hours']);

        return back()->with('success', 'Informasi kontak operasional admin berhasil diperbarui!');
    }

    public function storeHelpTopic(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $targetRole = $request->input('target_role', $request->input('role_target', 'semua'));
        if ($targetRole === 'pembeli') $targetRole = 'konsumen';
        if ($targetRole === 'all') $targetRole = 'semua';

        $orderNum = $request->input('order_num', $request->input('order_index', 0));

        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        HelpTopic::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'target_role' => in_array($targetRole, ['semua', 'petani', 'konsumen']) ? $targetRole : 'semua',
            'order_num' => (int) $orderNum,
        ]);

        return back()->with('success', 'Topik bantuan baru berhasil ditambahkan!');
    }

    public function updateHelpTopic(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $topic = HelpTopic::findOrFail($id);

        $targetRole = $request->input('target_role', $request->input('role_target', $topic->target_role));
        if ($targetRole === 'pembeli') $targetRole = 'konsumen';
        if ($targetRole === 'all') $targetRole = 'semua';

        $orderNum = $request->input('order_num', $request->input('order_index', $topic->order_num));

        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $topic->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
            'target_role' => in_array($targetRole, ['semua', 'petani', 'konsumen']) ? $targetRole : 'semua',
            'order_num' => (int) $orderNum,
        ]);

        return back()->with('success', 'Topik bantuan berhasil diperbarui!');
    }

    public function destroyHelpTopic($id)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $topic = HelpTopic::findOrFail($id);
        $topic->delete();

        return back()->with('success', 'Topik bantuan berhasil dihapus!');
    }

    // ==========================================
    // 4. COMMON (PENGATURAN & BANTUAN)
    // ==========================================

    public function helpCenter()
    {
        $user = Auth::user();
        $targetRoles = ['semua'];
        if ($user) {
            if ($user->role === 'petani') {
                $targetRoles[] = 'petani';
            } elseif (in_array($user->role, ['pembeli', 'konsumen'])) {
                $targetRoles[] = 'konsumen';
                $targetRoles[] = 'pembeli';
            } elseif ($user->role === 'admin') {
                $targetRoles = ['semua', 'petani', 'konsumen', 'pembeli'];
            }
        }

        // Urutan: pertanyaan yang dimasukkan pertama menjadi paling bawah (id desc)
        $topics = HelpTopic::whereIn('target_role', $targetRoles)
            ->orderBy('id', 'desc')
            ->get();

        $adminPhone = SystemSetting::get('admin_phone', '+62 812-3456-7890');
        $adminEmail = SystemSetting::get('admin_email', 'support@tanilink.id');
        $adminHours = SystemSetting::get('admin_hours', 'Senin - Sabtu (08.00 - 17.00 WIB)');

        return view('tanilink.help', compact('topics', 'user', 'adminPhone', 'adminEmail', 'adminHours'));
    }

    public function settings()
    {
        $user = Auth::user();
        if ($user->role === 'petani') {
            return redirect()->route('petani.profil');
        }
        if ($user->role === 'pembeli') {
            return redirect()->route('pembeli.profil');
        }
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

        return back()->with('success', 'Pengaturan profil berhasil diperbarui!');
    }
}
