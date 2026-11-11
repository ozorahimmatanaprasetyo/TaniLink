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
use App\Models\CategoryProposal;
use App\Models\HarvestBatch;
use App\Models\OrderActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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

        $myListings = CommodityListing::with('harvestBatches')->where('user_id', $petani->id)->latest()->get();

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

        $myListings = CommodityListing::with('harvestBatches')->where('user_id', $petani->id)->latest()->get();
        $activeOrders = PreOrder::where('farmer_id', $petani->id)->get();

        $dpReceived = $activeOrders->sum('dp_amount');
        $totalCropKg = $myListings->sum('estimated_harvest_kg');
        $myProposals = CategoryProposal::where('user_id', $petani->id)->latest()->get();

        return view('tanilink.farmer.listings', compact('petani', 'myListings', 'dpReceived', 'totalCropKg', 'myProposals'));
    }

    public function petaniCreateListing(Request $request)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $approvedCategories = CategoryProposal::where('status', 'disetujui')->pluck('name')->toArray();
        $availableCategories = array_values(array_unique(array_merge(['Cabai & Bumbu', 'Sayuran', 'Palawija', 'Buah', 'Biji-bijian'], $approvedCategories)));
        $myProposals = CategoryProposal::where('user_id', $petani->id)->latest()->get();

        return view('tanilink.farmer.create', compact('petani', 'availableCategories', 'myProposals'));
    }

    public function storeListing(Request $request)
    {
        $petani = Auth::user();

        $validated = $request->validate([
            'commodity_name' => 'required|string|max:255',
            'category' => 'required|string',
            'proposed_category' => 'nullable|string|max:100',
            'variety' => 'nullable|string|max:100',
            'grade' => 'nullable|string|max:50',
            'land_area_m2' => 'required|numeric|min:1',
            'estimated_harvest_kg' => 'required|numeric|min:1',
            'available_kg' => 'nullable|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:100',
            'harvest_estimated_date' => 'required|date',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        if (!$request->hasFile('image')) {
            return back()->withErrors(['image' => 'Silakan unggah foto komoditas terlebih dahulu.'])->withInput();
        }

        $category = $validated['category'];
        if ($category === 'Lainnya' || $category === 'Lainnya / Ajukan Kategori Baru') {
            if ($request->filled('proposed_category')) {
                $propName = trim(strip_tags($request->proposed_category));
                if (!empty($propName)) {
                    $exists = CategoryProposal::where('user_id', $petani->id)
                        ->whereRaw('LOWER(name) = ?', [strtolower($propName)])
                        ->exists();
                    if (!$exists) {
                        CategoryProposal::create([
                            'user_id' => $petani->id,
                            'name' => $propName,
                            'status' => 'menunggu_persetujuan',
                        ]);
                    }
                }
            }
            $category = 'Lainnya';
        }

        $path = $request->file('image')->store('listings', 'public');
        $imageUrl = '/storage/' . $path;

        $avail = $request->filled('available_kg') ? min((float)$validated['available_kg'], (float)$validated['estimated_harvest_kg']) : (float)$validated['estimated_harvest_kg'];

        $listing = CommodityListing::create([
            'user_id' => $petani->id,
            'commodity_name' => $validated['commodity_name'],
            'category' => $category,
            'variety' => $validated['variety'] ?? null,
            'grade' => !empty($validated['grade']) ? $validated['grade'] : 'Standar',
            'land_area_m2' => $validated['land_area_m2'],
            'estimated_harvest_kg' => $validated['estimated_harvest_kg'],
            'available_kg' => $avail,
            'price_per_kg' => $validated['price_per_kg'],
            'harvest_estimated_date' => $validated['harvest_estimated_date'],
            'location' => $petani->location ?? 'Malang, Jawa Timur',
            'latitude' => $petani->latitude,
            'longitude' => $petani->longitude,
            'image_url' => $imageUrl,
            'description' => $validated['description'] ?? null,
            'status' => 'available',
        ]);

        $harvestDate = $listing->harvest_estimated_date ?: now()->addDays(60)->toDateString();
        $monthYear = date('M Y', strtotime($harvestDate));
        HarvestBatch::create([
            'commodity_listing_id' => $listing->id,
            'farmer_id' => $petani->id,
            'batch_code' => 'BATCH-' . str_pad($listing->id, 4, '0', STR_PAD_LEFT) . '-01',
            'batch_name' => 'Batch Panen ' . $monthYear,
            'estimated_harvest_date' => $harvestDate,
            'estimated_harvest_kg' => $listing->estimated_harvest_kg,
            'allocated_kg' => 0,
            'current_phase' => 'Persiapan & Penanaman',
            'current_phase_status' => 'berlangsung',
            'status' => 'aktif',
        ]);

        return redirect()->route('petani.komoditas')->with('success', 'Komoditas dan Batch Panen perdana berhasil diterbitkan!');
    }

    public function petaniEditListing($id)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);
        $approvedCategories = CategoryProposal::where('status', 'disetujui')->pluck('name')->toArray();
        $availableCategories = array_values(array_unique(array_merge(['Cabai & Bumbu', 'Sayuran', 'Palawija', 'Buah', 'Biji-bijian'], $approvedCategories)));
        $myProposals = CategoryProposal::where('user_id', $petani->id)->latest()->get();

        return view('tanilink.farmer.edit', compact('petani', 'listing', 'availableCategories', 'myProposals'));
    }

    public function petaniUpdateListing(Request $request, $id)
    {
        $petani = Auth::user();

        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);

        $validated = $request->validate([
            'commodity_name' => 'required|string|max:255',
            'category' => 'required|string',
            'proposed_category' => 'nullable|string|max:100',
            'variety' => 'nullable|string|max:100',
            'grade' => 'nullable|string|max:50',
            'land_area_m2' => 'required|numeric|min:1',
            'estimated_harvest_kg' => 'required|numeric|min:1',
            'available_kg' => 'nullable|numeric|min:0',
            'price_per_kg' => 'required|numeric|min:100',
            'harvest_estimated_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'status' => 'nullable|string|in:available,booked',
        ]);

        if (!$listing->image_url && !$request->hasFile('image')) {
            return back()->withErrors(['image' => 'Silakan unggah foto komoditas terlebih dahulu.'])->withInput();
        }

        $category = $validated['category'];
        if ($category === 'Lainnya' || $category === 'Lainnya / Ajukan Kategori Baru') {
            if ($request->filled('proposed_category')) {
                $propName = trim(strip_tags($request->proposed_category));
                if (!empty($propName)) {
                    $exists = CategoryProposal::where('user_id', $petani->id)
                        ->whereRaw('LOWER(name) = ?', [strtolower($propName)])
                        ->exists();
                    if (!$exists) {
                        CategoryProposal::create([
                            'user_id' => $petani->id,
                            'name' => $propName,
                            'status' => 'menunggu_persetujuan',
                        ]);
                    }
                }
            }
            $category = 'Lainnya';
        }

        $data = [
            'commodity_name' => $validated['commodity_name'],
            'category' => $category,
            'variety' => $validated['variety'] ?? $listing->variety,
            'grade' => !empty($validated['grade']) ? $validated['grade'] : ($listing->grade ?: 'Standar'),
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

        // Query Pesanan Aktif
        $activeOrders = PreOrder::with(['commodityListing', 'buyer', 'harvestBatch', 'deliveryTracking'])
            ->where('farmer_id', $petani->id)
            ->whereNotIn('status', ['selesai', 'dibatalkan'])
            ->latest()
            ->get();

        // Query Riwayat Pesanan (Selesai & Dibatalkan/Ditolak)
        $historyQuery = PreOrder::with(['commodityListing', 'buyer', 'harvestBatch', 'deliveryTracking'])
            ->where('farmer_id', $petani->id)
            ->whereIn('status', ['selesai', 'dibatalkan']);

        // Filter pencarian kode pesanan & komoditas
        if ($request->filled('q')) {
            $q = trim($request->q);
            $historyQuery->where(function($query) use ($q) {
                $query->where('order_code', 'like', "%{$q}%")
                      ->orWhereHas('commodityListing', function($sq) use ($q) {
                          $sq->where('commodity_name', 'like', "%{$q}%");
                      })
                      ->orWhereHas('buyer', function($sq) use ($q) {
                          $sq->where('name', 'like', "%{$q}%");
                      });
            });
        }

        // Filter status riwayat
        if ($request->filled('status')) {
            if ($request->status === 'selesai') {
                $historyQuery->where('status', 'selesai');
            } elseif ($request->status === 'dibatalkan') {
                $historyQuery->where('status', 'dibatalkan');
            }
        }

        $historyOrders = $historyQuery->latest()->get();

        $totalKg = $activeOrders->sum('quantity_kg');
        $totalContract = $activeOrders->sum('total_amount');
        $dpReceived = $activeOrders->sum('dp_amount');

        return view('tanilink.farmer.orders', compact('petani', 'activeOrders', 'historyOrders', 'totalKg', 'totalContract', 'dpReceived'));
    }

    public function petaniOrderProgress($id)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $order = PreOrder::with(['commodityListing', 'buyer', 'plantingProgress', 'deliveryTracking', 'harvestBatch.plantingProgress'])
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
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
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
            'bank_name' => 'nullable|string|max:100',
            'bank_account' => 'nullable|string|max:100',
            'bank_holder' => 'nullable|string|max:150',
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $petani->organization,
            'phone' => $validated['phone'] ?? $petani->phone,
            'location' => $validated['location'] ?? $petani->location,
            'bank_name' => $validated['bank_name'] ?? $petani->bank_name,
            'bank_account' => $validated['bank_account'] ?? $petani->bank_account,
            'bank_holder' => $validated['bank_holder'] ?? $petani->bank_holder,
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

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('commodity_name', 'like', "%{$search}%")
                  ->orWhere('variety', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price_per_kg', '>=', (float)$request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_kg', '<=', (float)$request->max_price);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('harvest_estimated_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('harvest_estimated_date', '<=', $request->end_date);
        }

        $catalog = $query->latest()->get();

        return view('tanilink.buyer.catalog', compact('pembeli', 'catalog'));
    }

    public function pembeliOrders(Request $request)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'harvestBatch', 'deliveryTracking'])
            ->where('buyer_id', $pembeli->id)
            ->latest()
            ->get();

        // Query Pesanan Aktif
        $activeOrders = $allOrders->whereNotIn('status', ['selesai', 'dibatalkan']);

        // Query Riwayat Pesanan (Selesai & Dibatalkan/Ditolak)
        $historyQuery = PreOrder::with(['commodityListing', 'farmer', 'harvestBatch', 'deliveryTracking'])
            ->where('buyer_id', $pembeli->id)
            ->whereIn('status', ['selesai', 'dibatalkan']);

        // Filter pencarian kode pesanan & komoditas
        if ($request->filled('q')) {
            $q = trim($request->q);
            $historyQuery->where(function($query) use ($q) {
                $query->where('order_code', 'like', "%{$q}%")
                      ->orWhereHas('commodityListing', function($sq) use ($q) {
                          $sq->where('commodity_name', 'like', "%{$q}%");
                      })
                      ->orWhereHas('farmer', function($sq) use ($q) {
                          $sq->where('name', 'like', "%{$q}%");
                      });
            });
        }

        // Filter status riwayat
        if ($request->filled('status')) {
            if ($request->status === 'selesai') {
                $historyQuery->where('status', 'selesai');
            } elseif ($request->status === 'dibatalkan') {
                $historyQuery->where('status', 'dibatalkan');
            }
        }

        $historyOrders = $historyQuery->latest()->get();

        $totalPreOrderValue = $activeOrders->sum('total_amount');
        $escrowHeld = $activeOrders->sum('escrow_locked_amount');

        return view('tanilink.buyer.orders', compact('pembeli', 'allOrders', 'activeOrders', 'historyOrders', 'totalPreOrderValue', 'escrowHeld'));
    }

    public function pembeliTracking($id)
    {
        $pembeli = Auth::user();
        session(['active_role' => 'pembeli']);

        $order = PreOrder::with(['commodityListing', 'farmer', 'plantingProgress', 'deliveryTracking', 'harvestBatch.plantingProgress'])
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

        return DB::transaction(function () use ($validated, $qty, $pembeli) {
            $listing = CommodityListing::where('id', $validated['listing_id'])->lockForUpdate()->firstOrFail();

            if ($listing->status !== 'available' || $listing->available_kg <= 0) {
                return back()->withErrors(['error' => 'Komoditas ini sudah tidak tersedia.'])->withInput();
            }
            if ($qty > $listing->available_kg) {
                return back()->withErrors(['error' => "Stok alokasi tidak mencukupi. Tersisa: {$listing->available_kg} kg."])->withInput();
            }

            // Cari batch aktif untuk listing ini (atau buat secara otomatis jika belum ada)
            $batch = HarvestBatch::where('commodity_listing_id', $listing->id)
                ->where('status', 'aktif')
                ->lockForUpdate()
                ->first();

            if (!$batch) {
                $harvestDate = $listing->harvest_estimated_date ?: now()->addDays(60)->toDateString();
                $monthYear = date('M Y', strtotime($harvestDate));
                $batch = HarvestBatch::create([
                    'commodity_listing_id' => $listing->id,
                    'farmer_id' => $listing->user_id,
                    'batch_code' => 'BATCH-' . str_pad($listing->id, 4, '0', STR_PAD_LEFT) . '-01',
                    'batch_name' => 'Batch Panen ' . $monthYear,
                    'estimated_harvest_date' => $harvestDate,
                    'estimated_harvest_kg' => max((float)$listing->estimated_harvest_kg, $qty),
                    'allocated_kg' => 0,
                    'current_phase' => 'Persiapan & Penanaman',
                    'current_phase_status' => 'berlangsung',
                    'status' => 'aktif',
                ]);
            }

            if ($qty > $batch->available_kg) {
                // Jika alokasi batch belum disesuaikan tapi listing mencukupi, naikkan estimasi batch
                if ($qty <= $listing->available_kg) {
                    $batch->update(['estimated_harvest_kg' => $batch->allocated_kg + $listing->available_kg]);
                } else {
                    return back()->withErrors(['error' => "Kuota batch panen tidak mencukupi. Tersisa: {$batch->available_kg} kg."])->withInput();
                }
            }

            $total = $qty * $listing->price_per_kg;
            $dp = $total * 0.3;
            $remaining = $total * 0.7;

            // Snapshot informasi bank petani ke dalam pesanan
            $farmer = User::find($listing->user_id);

            $order = PreOrder::create([
                'order_code' => 'TL-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4)),
                'commodity_listing_id' => $listing->id,
                'harvest_batch_id' => $batch->id,
                'farmer_id' => $listing->user_id,
                'buyer_id' => $pembeli->id,
                'quantity_kg' => $qty,
                'price_per_kg' => $listing->price_per_kg,
                'need_date' => $validated['need_date'] ?? $listing->harvest_estimated_date,
                'notes' => $validated['notes'] ?? null,
                'total_amount' => $total,
                'dp_amount' => $dp,
                'escrow_locked_amount' => $remaining,
                'farmer_bank_name' => $farmer->bank_name ?? null,
                'farmer_bank_account' => $farmer->bank_account ?? null,
                'farmer_bank_holder' => $farmer->bank_holder ?: ($farmer->name ?? null),
                'status' => 'menunggu_konfirmasi',
                'current_stage' => 'Menunggu Konfirmasi Petani',
                'stage_percentage' => 5,
            ]);

            DeliveryTracking::create([
                'pre_order_id' => $order->id,
                'pickup_address' => 'Lahan Petani, ' . $listing->location,
                'destination_address' => ($pembeli->organization ?? 'Lokasi Konsumen') . ', ' . ($pembeli->location ?? 'Alamat Pemesan'),
                'status' => 'pending',
                'driver_name' => 'Belum ditentukan',
                'driver_vehicle' => 'Belum ditentukan',
                'cargo_crates' => max(1, ceil($qty / 20)),
            ]);

            // Alokasi kuota pada batch dan listing
            $batch->increment('allocated_kg', $qty);
            $listing->decrement('available_kg', $qty);
            if ($listing->available_kg <= 0) {
                $listing->update(['status' => 'booked']);
            }

            OrderActivity::log(
                $order->id,
                'order_created',
                'Pengajuan Pre-Order',
                'Konsumen mengajukan pesanan sejumlah ' . number_format($qty, 0, ',', '.') . ' kg.',
                $pembeli->id,
                now('Asia/Jakarta')
            );

            return redirect()->route('pembeli.pesanan')->with('success', 'Pengajuan pre-order berhasil dikirim! Menunggu persetujuan petani.');
        });
    }

    public function petaniConfirmOrder($id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pesanan ini sudah dikonfirmasi atau diproses sebelumnya.');
        }

        // Snapshot bank petani saat konfirmasi (jika belum tersnapshot saat booking)
        $updateData = [
            'status' => 'menunggu_pembayaran_dp',
            'confirmed_at' => now(),
            'current_stage' => 'Menunggu Pembayaran DP',
            'stage_percentage' => 10,
            'dp_deadline_at' => now()->addDays(3),
        ];

        if (empty($order->farmer_bank_name)) {
            $updateData['farmer_bank_name'] = $petani->bank_name;
            $updateData['farmer_bank_account'] = $petani->bank_account;
            $updateData['farmer_bank_holder'] = $petani->bank_holder ?: $petani->name;
        }

        $order->update($updateData);

        OrderActivity::log(
            $order->id,
            'order_confirmed',
            'Pesanan Disetujui Petani',
            'Petani menyetujui pesanan. Instruksi pembayaran DP 30% sebesar Rp ' . number_format($order->dp_amount, 0, ',', '.') . ' diterbitkan.',
            $petani->id,
            now('Asia/Jakarta')
        );

        return back()->with('success', 'Pesanan ' . $order->order_code . ' disetujui. Menunggu pembayaran DP dari konsumen.');
    }

    public function petaniRejectOrder(Request $request, $id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pesanan ini tidak dapat ditolak karena sudah dalam proses.');
        }

        $reason = $request->input('reason', 'Kapasitas lahan atau jadwal panen belum memungkinkan.');

        DB::transaction(function () use ($order, $reason, $petani) {
            $order->update([
                'status' => 'dibatalkan',
                'rejection_reason' => $reason,
                'rejected_at' => now('Asia/Jakarta'),
                'current_stage' => 'Ditolak Petani',
                'current_stage_status' => 'selesai',
            ]);

            OrderActivity::log(
                $order->id,
                'order_rejected',
                'Pesanan Ditolak Petani',
                'Alasan penolakan: ' . $reason,
                $petani->id,
                now('Asia/Jakarta')
            );

            // Kembalikan kuota batch
            if ($order->harvest_batch_id && $order->harvestBatch) {
                $order->harvestBatch->decrement('allocated_kg', $order->quantity_kg);
            }

            // Kembalikan stok alokasi komoditas
            if ($order->commodityListing) {
                $order->commodityListing->increment('available_kg', $order->quantity_kg);
                if ($order->commodityListing->status === 'booked' && $order->commodityListing->available_kg > 0) {
                    $order->commodityListing->update(['status' => 'available']);
                }
            }
        });

        return back()->with('success', 'Pesanan ' . $order->order_code . ' telah ditolak dan stok telah dikembalikan.');
    }

    // ==========================================
    // ALUR DP & BATCH PANEN
    // ==========================================

    /**
     * Konsumen mengunggah bukti pembayaran DP
     */
    public function pembeliUploadDpProof(Request $request, $id)
    {
        $pembeli = Auth::user();
        $order = PreOrder::where('buyer_id', $pembeli->id)->findOrFail($id);

        if ($order->status !== 'menunggu_pembayaran_dp') {
            return back()->with('error', 'Bukti pembayaran hanya dapat diunggah saat status Menunggu Pembayaran DP.');
        }

        $request->validate([
            'dp_proof' => 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        // Simpan di storage/app/payment_proofs (private, tidak publik)
        $path = $request->file('dp_proof')->store('payment_proofs');

        $order->update([
            'dp_proof_path' => $path,
            'dp_uploaded_at' => now('Asia/Jakarta'),
            'dp_rejection_note' => null,
            'status' => 'menunggu_verifikasi_dp',
            'current_stage' => 'Menunggu Pemeriksaan Pembayaran',
            'stage_percentage' => 15,
        ]);

        OrderActivity::log(
            $order->id,
            'dp_proof_uploaded',
            'Bukti Pembayaran DP Diunggah',
            'Konsumen mengunggah berkas bukti transfer DP sebesar Rp ' . number_format($order->dp_amount, 0, ',', '.') . '.',
            $pembeli->id,
            now('Asia/Jakarta')
        );

        return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu pemeriksaan oleh petani.');
    }

    /**
     * Lihat bukti DP (hanya pembeli, petani pemilik, atau admin)
     */
    public function viewDpProof($id)
    {
        $user = Auth::user();
        $order = PreOrder::findOrFail($id);

        if ($user->id !== $order->buyer_id && $user->id !== $order->farmer_id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        if (empty($order->dp_proof_path)) {
            return response('<div style="font-family:sans-serif; text-align:center; padding:50px; color:#475569;"><h3>Bukti Pembayaran Belum Diunggah</h3><p>Konsumen belum mengunggah berkas bukti transfer untuk pesanan ini.</p></div>', 404, ['Content-Type' => 'text/html']);
        }

        $fullPath = null;
        if (Storage::disk('local')->exists($order->dp_proof_path)) {
            $fullPath = Storage::disk('local')->path($order->dp_proof_path);
        } elseif (file_exists(storage_path('app/' . $order->dp_proof_path))) {
            $fullPath = storage_path('app/' . $order->dp_proof_path);
        }

        if (!$fullPath || !file_exists($fullPath)) {
            return response('<div style="font-family:sans-serif; text-align:center; padding:50px; color:#991B1B;"><h3>Berkas Tidak Ditemukan</h3><p>Berkas bukti pembayaran tidak ditemukan pada server penyimpanan. Silakan unggah ulang bukti pembayaran.</p></div>', 404, ['Content-Type' => 'text/html']);
        }

        $mimeType = mime_content_type($fullPath) ?: 'application/octet-stream';
        $filename = basename($fullPath);

        return response()->file($fullPath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    /**
     * Petani mengonfirmasi DP diterima
     */
    public function petaniConfirmDp($id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_verifikasi_dp') {
            return back()->with('error', 'Pesanan ini tidak dalam status menunggu verifikasi DP.');
        }

        $order->update([
            'status' => 'pesanan_aktif',
            'dp_confirmed_at' => now('Asia/Jakarta'),
            'current_stage' => 'Persiapan & Penanaman',
            'current_stage_status' => 'berlangsung',
            'stage_percentage' => 20,
        ]);

        OrderActivity::log(
            $order->id,
            'dp_confirmed',
            'Pembayaran DP Dikonfirmasi',
            'Petani telah mengonfirmasi mutasi transfer DP Rp ' . number_format($order->dp_amount, 0, ',', '.') . '. Kontrak budidaya resmi berjalan.',
            $petani->id,
            now('Asia/Jakarta')
        );

        return back()->with('success', 'DP untuk pesanan ' . $order->order_code . ' telah dikonfirmasi. Pesanan sekarang aktif.');
    }

    /**
     * Petani menolak bukti DP (minta perbaikan)
     */
    public function petaniRejectDp(Request $request, $id)
    {
        $petani = Auth::user();
        $order = PreOrder::where('farmer_id', $petani->id)->findOrFail($id);

        if ($order->status !== 'menunggu_verifikasi_dp') {
            return back()->with('error', 'Pesanan ini tidak dalam status menunggu verifikasi DP.');
        }

        $reason = $request->input('rejection_note', 'Bukti pembayaran tidak sesuai. Silakan unggah ulang.');

        $order->update([
            'status' => 'menunggu_pembayaran_dp',
            'dp_rejection_note' => $reason,
            'dp_proof_path' => null,
            'dp_uploaded_at' => null,
            'current_stage' => 'Menunggu Pembayaran DP',
            'stage_percentage' => 10,
        ]);

        OrderActivity::log(
            $order->id,
            'dp_rejected',
            'Bukti Pembayaran DP Diminta Perbaikan',
            'Catatan petani: ' . $reason,
            $petani->id,
            now('Asia/Jakarta')
        );

        return back()->with('success', 'Bukti pembayaran ditolak. Konsumen akan diminta mengunggah ulang.');
    }

    /**
     * Petani membuat batch panen baru untuk komoditas
     */
    public function storeBatch(Request $request, $id)
    {
        $petani = Auth::user();
        $listing = CommodityListing::where('user_id', $petani->id)->findOrFail($id);

        $validated = $request->validate([
            'batch_name' => 'required|string|max:255',
            'estimated_harvest_date' => 'required|date|after_or_equal:today',
            'estimated_harvest_kg' => 'required|numeric|min:1',
        ]);

        $existingCount = HarvestBatch::where('commodity_listing_id', $listing->id)->count();
        $batchNum = str_pad($existingCount + 1, 2, '0', STR_PAD_LEFT);

        HarvestBatch::create([
            'commodity_listing_id' => $listing->id,
            'farmer_id' => $petani->id,
            'batch_code' => 'BATCH-' . str_pad($listing->id, 4, '0', STR_PAD_LEFT) . '-' . $batchNum,
            'batch_name' => $validated['batch_name'],
            'estimated_harvest_date' => $validated['estimated_harvest_date'],
            'estimated_harvest_kg' => $validated['estimated_harvest_kg'],
            'allocated_kg' => 0,
            'current_phase' => 'Persiapan & Penanaman',
            'current_phase_status' => 'berlangsung',
            'status' => 'aktif',
        ]);

        return back()->with('success', 'Batch panen baru berhasil dibuat!');
    }

    /**
     * Halaman monitoring batch panen (petani)
     */
    public function petaniBatchProgress($id)
    {
        $petani = Auth::user();
        session(['active_role' => 'petani']);

        $batch = HarvestBatch::with(['commodityListing', 'plantingProgress', 'preOrders.buyer'])
            ->where('farmer_id', $petani->id)
            ->findOrFail($id);

        return view('tanilink.farmer.batch_progress', compact('petani', 'batch'));
    }

    /**
     * Petani mengunggah dokumentasi progres ke batch
     */
    public function storeBatchProgress(Request $request, $id)
    {
        $petani = Auth::user();
        $batch = HarvestBatch::where('farmer_id', $petani->id)->findOrFail($id);

        if ($batch->status !== 'aktif') {
            return back()->with('error', 'Batch panen ini sudah tidak aktif.');
        }

        if ($batch->current_phase_status !== 'berlangsung') {
            return back()->with('error', 'Tidak dapat menambah dokumentasi pada fase yang belum aktif atau sudah selesai.');
        }

        $validated = $request->validate([
            'note' => 'required|string|max:2000',
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('photo')->store('progress', 'public');
        $photoUrl = '/storage/' . $path;

        try {
            PlantingProgress::create([
                'harvest_batch_id' => $batch->id,
                'stage_name' => $batch->current_phase,
                'stage_status' => $batch->current_phase_status,
                'note' => $validated['note'],
                'photo_url' => $photoUrl,
                'location_tag' => $batch->commodityListing->location ?? 'Lahan Petani',
                'reported_at' => now('Asia/Jakarta'),
                'is_verified' => false,
            ]);

            // Catat aktivitas dokumentasi pada semua pre_order terikat
            $linkedOrders = PreOrder::where('harvest_batch_id', $batch->id)->get();
            foreach ($linkedOrders as $lo) {
                OrderActivity::log(
                    $lo->id,
                    'progress_uploaded',
                    'Dokumentasi ' . $batch->current_phase . ' Diunggah',
                    'Petani mengunggah dokumentasi kondisi lapangan: "' . \Illuminate\Support\Str::limit($validated['note'], 80) . '"',
                    $petani->id,
                    now('Asia/Jakarta')
                );
            }
        } catch (\Exception $e) {
            // Cleanup uploaded file if DB insert fails
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
            \Illuminate\Support\Facades\Log::error('storeBatchProgress failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan dokumentasi. Silakan coba lagi atau hubungi administrator.');
        }

        return back()->with('success', 'Dokumentasi progres fase "' . $batch->current_phase . '" berhasil disimpan!');
    }

    /**
     * Petani menandai fase batch selesai dan lanjut ke fase berikutnya
     */
    public function completeBatchPhase(Request $request, $id)
    {
        $petani = Auth::user();

        return DB::transaction(function () use ($petani, $id) {
            $batch = HarvestBatch::where('farmer_id', $petani->id)->lockForUpdate()->findOrFail($id);

            if ($batch->status !== 'aktif') {
                return back()->with('error', 'Batch panen ini sudah tidak aktif.');
            }

            $currentPhaseIndex = $batch->current_phase_index;

            // Validasi: minimal 1 dokumentasi di fase saat ini
            $docsCount = PlantingProgress::where('harvest_batch_id', $batch->id)
                ->where('stage_name', $batch->current_phase)
                ->count();

            if ($docsCount < 1) {
                return back()->with('error', 'Anda harus mengunggah minimal 1 dokumentasi (foto & catatan) pada fase ini sebelum menandainya selesai.');
            }

            // Tandai fase saat ini selesai
            $completedCol = "phase{$currentPhaseIndex}_completed_at";
            $batch->{$completedCol} = now();

            if ($currentPhaseIndex < 4) {
                // Lanjut ke fase berikutnya
                $nextPhase = HarvestBatch::$phases[$currentPhaseIndex + 1];
                $batch->current_phase = $nextPhase['name'];
                $batch->current_phase_status = 'berlangsung';

                $stagePercentages = [
                    2 => 45,
                    3 => 75,
                    4 => 95,
                ];

                PreOrder::where('harvest_batch_id', $batch->id)
                    ->whereIn('status', ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan'])
                    ->update([
                        'current_stage' => $nextPhase['name'],
                        'current_stage_status' => 'berlangsung',
                        'stage_percentage' => $stagePercentages[$currentPhaseIndex + 1] ?? 50,
                    ]);
            } else {
                // Fase 4 selesai → batch siap panen
                $batch->current_phase_status = 'selesai';
                $batch->status = 'siap_panen';

                // Update semua pesanan aktif di batch ini menjadi siap_panen
                PreOrder::where('harvest_batch_id', $batch->id)
                    ->whereIn('status', ['pesanan_aktif', 'dikonfirmasi', 'dp_cair', 'tanam_berjalan'])
                    ->update([
                        'status' => 'siap_panen',
                        'current_stage' => 'Siap Panen',
                        'current_stage_status' => 'selesai',
                        'stage_percentage' => 100,
                    ]);
            }

            $batch->save();

            $phaseLabel = HarvestBatch::$phases[$currentPhaseIndex]['name'] ?? 'Fase';

            // Catat ke order_activities untuk setiap pre_order yang terikat pada batch ini
            $linkedOrders = PreOrder::where('harvest_batch_id', $batch->id)->get();
            foreach ($linkedOrders as $lo) {
                OrderActivity::log(
                    $lo->id,
                    'phase_completed',
                    'Fase ' . $phaseLabel . ' Diselesaikan',
                    'Petani telah menyelesaikan fase "' . $phaseLabel . '". ' . ($currentPhaseIndex < 4 ? 'Tanaman berlanjut ke ' . $batch->current_phase . '.' : 'Batch panen telah siap panen!'),
                    $petani->id,
                    now('Asia/Jakarta')
                );
            }

            return back()->with('success', 'Fase "' . $phaseLabel . '" berhasil ditandai selesai!');
        });
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
            'completed_at' => now('Asia/Jakarta'),
            'current_stage' => 'Fase Pematangan & Siap Panen',
            'current_stage_status' => 'selesai',
            'stage_percentage' => 100,
        ]);

        if ($order->deliveryTracking) {
            $order->deliveryTracking->update(['status' => 'delivered']);
        }

        OrderActivity::log(
            $order->id,
            'order_completed',
            'Pesanan Selesai & Diterima Konsumen',
            'Konsumen telah mengonfirmasi penerimaan hasil panen komoditas dengan baik. Transaksi pre-order selesai.',
            $pembeli->id,
            now('Asia/Jakarta')
        );

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
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $validated['name'],
            'organization' => $validated['organization'] ?? $pembeli->organization,
            'phone' => $validated['phone'] ?? $pembeli->phone,
            'location' => $validated['location'] ?? $pembeli->location,
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

        // Data statistik aktual dari database
        $stats = $this->getAdminActualStats();

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'buyer', 'harvestBatch', 'deliveryTracking'])
            ->latest()
            ->take(10)
            ->get();

        return view('tanilink.admin', compact('admin', 'stats', 'allOrders'));
    }

    /**
     * Endpoint API JSON untuk Polling Ringan Statistik Dashboard Admin
     */
    public function adminStatsApi()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $stats = $this->getAdminActualStats();

        return response()->json([
            'status' => 'success',
            'timestamp' => now('Asia/Jakarta')->translatedFormat('d M Y, H:i:s') . ' WIB',
            'data' => $stats,
        ]);
    }

    /**
     * Helper perhitungan statistik admin terpusat, konsisten, dan akurat
     */
    private function getAdminActualStats(): array
    {
        $totalFarmers = User::where('role', 'petani')->count();
        $totalBuyers = User::where('role', 'pembeli')->count();
        $totalUsers = $totalFarmers + $totalBuyers;

        $activeListings = CommodityListing::count();
        $activeBatches = HarvestBatch::where('status', 'aktif')->count();
        $totalBatches = HarvestBatch::count();

        // Pesanan aktif (bukan selesai dan bukan dibatalkan)
        $activeOrders = PreOrder::whereNotIn('status', ['selesai', 'dibatalkan'])->count();
        $pendingConfirmation = PreOrder::where('status', 'menunggu_konfirmasi')->count();
        $pendingDpPayment = PreOrder::where('status', 'menunggu_pembayaran_dp')->count();
        $pendingDpVerification = PreOrder::where('status', 'menunggu_verifikasi_dp')->count();
        $completedOrders = PreOrder::where('status', 'selesai')->count();
        $cancelledOrders = PreOrder::where('status', 'dibatalkan')->count();
        $totalOrders = PreOrder::count();

        // Finansial aktual:
        // Total Nilai Pesanan dihitung dari pesanan sah (tidak termasuk yang ditolak/dibatalkan)
        $validOrdersQuery = PreOrder::where('status', '!=', 'dibatalkan');
        $totalOrderValue = (float) $validOrdersQuery->sum('total_amount');
        
        // DP yang sudah dikonfirmasi petani
        $confirmedDpTotal = (float) PreOrder::whereNotNull('dp_confirmed_at')
            ->where('status', '!=', 'dibatalkan')
            ->sum('dp_amount');

        // DP yang masih menunggu verifikasi / pemeriksaan
        $unverifiedDpTotal = (float) PreOrder::where('status', 'menunggu_verifikasi_dp')
            ->sum('dp_amount');

        return [
            'total_farmers' => $totalFarmers,
            'total_buyers' => $totalBuyers,
            'total_users' => $totalUsers,
            'active_listings' => $activeListings,
            'active_batches' => $activeBatches,
            'total_batches' => $totalBatches,
            'active_orders' => $activeOrders,
            'pending_confirmation' => $pendingConfirmation,
            'pending_dp_payment' => $pendingDpPayment,
            'pending_dp_verification' => $pendingDpVerification,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'total_orders' => $totalOrders,
            'total_order_value' => $totalOrderValue,
            'confirmed_dp_total' => $confirmedDpTotal,
            'unverified_dp_total' => $unverifiedDpTotal,
            'total_order_value_formatted' => 'Rp ' . number_format($totalOrderValue, 0, ',', '.'),
            'confirmed_dp_formatted' => 'Rp ' . number_format($confirmedDpTotal, 0, ',', '.'),
            'unverified_dp_formatted' => 'Rp ' . number_format($unverifiedDpTotal, 0, ',', '.'),
        ];
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
        $categoryProposals = CategoryProposal::with('user')->latest()->get();

        return view('tanilink.admin.listings', compact('admin', 'allListings', 'categoryProposals'));
    }

    public function adminApproveCategory($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $proposal = CategoryProposal::findOrFail($id);
        $proposal->update(['status' => 'disetujui']);

        // Perbarui komoditas petani pengusul yang berkategori 'Lainnya' jika relevan
        CommodityListing::where('user_id', $proposal->user_id)
            ->where('category', 'Lainnya')
            ->update(['category' => $proposal->name]);

        return back()->with('success', 'Usulan kategori "' . $proposal->name . '" telah disetujui dan aktif di sistem!');
    }

    public function adminRejectCategory(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $proposal = CategoryProposal::findOrFail($id);
        $proposal->update([
            'status' => 'ditolak',
            'admin_note' => $request->input('admin_note', 'Kategori sejenis sudah tersedia atau belum memenuhi standar.')
        ]);

        return back()->with('success', 'Usulan kategori "' . $proposal->name . '" telah ditolak.');
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

        $allOrders = PreOrder::with(['commodityListing', 'farmer', 'buyer', 'harvestBatch', 'deliveryTracking', 'activities'])
            ->latest()
            ->get();

        $stats = $this->getAdminActualStats();

        return view('tanilink.admin.orders', compact('admin', 'allOrders', 'stats'));
    }

    public function adminReports(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('home');
        }

        $admin = Auth::user();
        session(['active_role' => 'admin']);

        // Parse filter parameters
        $filters = $this->parseReportFilters($request);
        [$periodStart, $periodEnd] = $this->getReportPeriodRange($filters);

        // Compute period-filtered stats
        $stats = $this->getFilteredReportStats($filters, $periodStart, $periodEnd);

        // Compute chart datasets based on period & actual DB data
        $chartData = $this->getReportChartData($filters, $periodStart, $periodEnd);

        // Get filtered data for display
        $allOrders = $this->getFilteredOrders($periodStart, $periodEnd);
        $allUsers = User::where('role', '!=', 'admin')->get();
        $allListings = CommodityListing::with(['farmer', 'harvestBatches'])->get();

        return view('tanilink.admin.reports', compact('admin', 'allOrders', 'allUsers', 'allListings', 'stats', 'filters', 'chartData'));
    }

    /**
     * Unduh Laporan PDF Admin berdasarkan periode dan jenis laporan
     */
    public function adminReportPdf(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $filters = $this->parseReportFilters($request);
        [$periodStart, $periodEnd] = $this->getReportPeriodRange($filters);
        $stats = $this->getFilteredReportStats($filters, $periodStart, $periodEnd);
        $orders = $this->getFilteredOrders($periodStart, $periodEnd);

        $reportTypeLabels = [
            'keseluruhan' => 'Laporan Keseluruhan',
            'pesanan' => 'Laporan Pesanan & Transaksi',
            'pembayaran' => 'Laporan Pembayaran DP',
            'komoditas' => 'Laporan Komoditas & Panen',
            'pengguna' => 'Laporan Pengguna',
        ];
        $reportTitle = $reportTypeLabels[$filters['report_type']] ?? 'Laporan Keseluruhan';
        $periodLabel = $this->getReportPeriodLabel($filters);
        $generatedAt = now('Asia/Jakarta')->translatedFormat('d F Y, H:i:s') . ' WIB';

        // Monthly breakdown for yearly reports
        $monthlyBreakdown = null;
        if ($filters['period_type'] === 'tahunan') {
            $monthlyBreakdown = [];
            $monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            for ($m = 1; $m <= 12; $m++) {
                $mStart = \Carbon\Carbon::create($filters['year'], $m, 1, 0, 0, 0, 'Asia/Jakarta');
                $mEnd = $mStart->copy()->endOfMonth();
                $mOrders = PreOrder::whereBetween('created_at', [$mStart->utc(), $mEnd->utc()]);
                $mValidOrders = (clone $mOrders)->where('status', '!=', 'dibatalkan');
                $monthlyBreakdown[] = [
                    'month' => $monthNames[$m - 1],
                    'orders_count' => (clone $mOrders)->count(),
                    'orders_completed' => (clone $mOrders)->where('status', 'selesai')->count(),
                    'total_value' => (float)(clone $mValidOrders)->sum('total_amount'),
                    'confirmed_dp' => (float) PreOrder::whereNotNull('dp_confirmed_at')
                        ->where('status', '!=', 'dibatalkan')
                        ->whereBetween('dp_confirmed_at', [$mStart->utc(), $mEnd->utc()])
                        ->sum('dp_amount'),
                ];
            }
        }

        $reportType = $filters['report_type'];
        $periodType = $filters['period_type'];

        $pdf = Pdf::loadView('tanilink.pdf.admin_report', compact(
            'reportTitle', 'periodLabel', 'periodType', 'generatedAt',
            'stats', 'orders', 'reportType', 'monthlyBreakdown'
        ))->setPaper('a4', $orders->count() > 0 ? 'landscape' : 'portrait');

        // Generate filename
        $filename = $this->generateReportFilename($filters);

        return $pdf->download($filename);
    }

    /**
     * Parse filter parameters for admin reports
     */
    private function parseReportFilters(Request $request): array
    {
        return [
            'period_type' => $request->input('period_type', 'bulanan'),
            'month' => (int) $request->input('month', now('Asia/Jakarta')->month),
            'year' => (int) $request->input('year', now('Asia/Jakarta')->year),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'report_type' => $request->input('report_type', 'keseluruhan'),
        ];
    }

    /**
     * Calculate period start and end timestamps from filters
     */
    private function getReportPeriodRange(array $filters): array
    {
        $tz = 'Asia/Jakarta';

        switch ($filters['period_type']) {
            case 'tahunan':
                $start = \Carbon\Carbon::create($filters['year'], 1, 1, 0, 0, 0, $tz);
                $end = $start->copy()->endOfYear();
                break;
            case 'rentang':
                $start = $filters['start_date']
                    ? \Carbon\Carbon::parse($filters['start_date'])->startOfDay()->timezone($tz)
                    : now($tz)->startOfMonth();
                $end = $filters['end_date']
                    ? \Carbon\Carbon::parse($filters['end_date'])->endOfDay()->timezone($tz)
                    : now($tz)->endOfDay();
                break;
            default: // bulanan
                $start = \Carbon\Carbon::create($filters['year'], $filters['month'], 1, 0, 0, 0, $tz);
                $end = $start->copy()->endOfMonth();
                break;
        }

        return [$start, $end];
    }

    /**
     * Get period label string for display/PDF
     */
    private function getReportPeriodLabel(array $filters): string
    {
        $monthNames = ['','Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

        switch ($filters['period_type']) {
            case 'tahunan':
                return 'Tahun ' . $filters['year'];
            case 'rentang':
                $s = $filters['start_date'] ? \Carbon\Carbon::parse($filters['start_date'])->translatedFormat('d F Y') : '-';
                $e = $filters['end_date'] ? \Carbon\Carbon::parse($filters['end_date'])->translatedFormat('d F Y') : '-';
                return $s . ' — ' . $e;
            default:
                return ($monthNames[$filters['month']] ?? '') . ' ' . $filters['year'];
        }
    }

    /**
     * Compute filtered stats for reports based on period
     */
    private function getFilteredReportStats(array $filters, $periodStart, $periodEnd): array
    {
        // Users registered in period
        $newFarmers = User::where('role', 'petani')->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $newBuyers = User::where('role', 'pembeli')->whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $totalFarmers = User::where('role', 'petani')->where('created_at', '<=', $periodEnd)->count();
        $totalBuyers = User::where('role', 'pembeli')->where('created_at', '<=', $periodEnd)->count();

        // Listings/batches in period
        $newListings = CommodityListing::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $activeListings = CommodityListing::where('created_at', '<=', $periodEnd)->count();
        $newBatches = HarvestBatch::whereBetween('created_at', [$periodStart, $periodEnd])->count();
        $completedBatches = HarvestBatch::where('status', 'selesai')->whereBetween('updated_at', [$periodStart, $periodEnd])->count();

        // Harvest estimates
        $totalEstimatedHarvest = (float) CommodityListing::where('created_at', '<=', $periodEnd)->sum('estimated_harvest_kg');
        $totalOrderedKg = (float) PreOrder::where('status', '!=', 'dibatalkan')->whereBetween('created_at', [$periodStart, $periodEnd])->sum('quantity_kg');

        // Orders submitted in period
        $periodOrders = PreOrder::whereBetween('created_at', [$periodStart, $periodEnd]);
        $ordersSubmitted = (clone $periodOrders)->count();
        $ordersPending = (clone $periodOrders)->where('status', 'menunggu_konfirmasi')->count();
        $ordersActive = (clone $periodOrders)->whereNotIn('status', ['selesai', 'dibatalkan', 'menunggu_konfirmasi'])->count();
        $ordersCompleted = (clone $periodOrders)->where('status', 'selesai')->count();
        $ordersCancelled = (clone $periodOrders)->where('status', 'dibatalkan')->count();

        // Financial — only from valid (non-cancelled) orders in period
        $validPeriodOrders = PreOrder::where('status', '!=', 'dibatalkan')->whereBetween('created_at', [$periodStart, $periodEnd]);
        $totalOrderValue = (float) (clone $validPeriodOrders)->sum('total_amount');

        // DP confirmed in period (by dp_confirmed_at timestamp)
        $confirmedDpTotal = (float) PreOrder::whereNotNull('dp_confirmed_at')
            ->where('status', '!=', 'dibatalkan')
            ->whereBetween('dp_confirmed_at', [$periodStart, $periodEnd])
            ->sum('dp_amount');

        // DP pending verification (uploaded but not confirmed, within period)
        $unverifiedDpTotal = (float) PreOrder::where('status', 'menunggu_verifikasi_dp')
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->sum('dp_amount');

        // Final payment confirmed in period
        $finalPaymentTotal = (float) PreOrder::whereNotNull('final_payment_confirmed_at')
            ->where('status', '!=', 'dibatalkan')
            ->whereBetween('final_payment_confirmed_at', [$periodStart, $periodEnd])
            ->sum('escrow_locked_amount');

        return [
            'new_farmers' => $newFarmers,
            'new_buyers' => $newBuyers,
            'total_farmers' => $totalFarmers,
            'total_buyers' => $totalBuyers,
            'new_listings' => $newListings,
            'active_listings' => $activeListings,
            'new_batches' => $newBatches,
            'completed_batches' => $completedBatches,
            'total_estimated_harvest' => $totalEstimatedHarvest,
            'total_ordered_kg' => $totalOrderedKg,
            'orders_submitted' => $ordersSubmitted,
            'orders_pending' => $ordersPending,
            'orders_active' => $ordersActive,
            'orders_completed' => $ordersCompleted,
            'orders_cancelled' => $ordersCancelled,
            'total_order_value' => $totalOrderValue,
            'total_order_value_formatted' => 'Rp ' . number_format($totalOrderValue, 0, ',', '.'),
            'confirmed_dp_total' => $confirmedDpTotal,
            'confirmed_dp_formatted' => 'Rp ' . number_format($confirmedDpTotal, 0, ',', '.'),
            'unverified_dp_total' => $unverifiedDpTotal,
            'unverified_dp_formatted' => 'Rp ' . number_format($unverifiedDpTotal, 0, ',', '.'),
            'final_payment_total' => $finalPaymentTotal,
            'final_payment_formatted' => 'Rp ' . number_format($finalPaymentTotal, 0, ',', '.'),
        ];
    }

    /**
     * Helper menyiapkan 4 dataset grafik statistik teragregasi langsung dari database
     */
    private function getReportChartData(array $filters, $periodStart, $periodEnd): array
    {
        $tz = 'Asia/Jakarta';

        // -------------------------------------------------------------
        // A. Grafik Batang (Orders Count) & C. Grafik Garis (Order Value)
        // -------------------------------------------------------------
        $timelineLabels = [];
        $timelineOrders = [];
        $timelineValues = [];

        if ($filters['period_type'] === 'tahunan') {
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            for ($m = 1; $m <= 12; $m++) {
                $mStart = \Carbon\Carbon::create($filters['year'], $m, 1, 0, 0, 0, $tz);
                $mEnd = $mStart->copy()->endOfMonth();

                $count = PreOrder::whereBetween('created_at', [$mStart->utc(), $mEnd->utc()])->count();
                $val = (float) PreOrder::where('status', '!=', 'dibatalkan')
                    ->whereBetween('created_at', [$mStart->utc(), $mEnd->utc()])
                    ->sum('total_amount');

                $timelineLabels[] = $monthNames[$m - 1];
                $timelineOrders[] = $count;
                $timelineValues[] = $val;
            }
        } elseif ($filters['period_type'] === 'bulanan') {
            $daysInMonth = $periodStart->daysInMonth;
            // 4 Mingguan
            $weeks = [
                ['label' => 'Minggu 1 (Tgl 1-7)', 'start' => 1, 'end' => 7],
                ['label' => 'Minggu 2 (Tgl 8-14)', 'start' => 8, 'end' => 14],
                ['label' => 'Minggu 3 (Tgl 15-21)', 'start' => 15, 'end' => 21],
                ['label' => 'Minggu 4 (Tgl 22-' . $daysInMonth . ')', 'start' => 22, 'end' => $daysInMonth],
            ];

            foreach ($weeks as $w) {
                $wStart = \Carbon\Carbon::create($filters['year'], $filters['month'], $w['start'], 0, 0, 0, $tz);
                $wEnd = \Carbon\Carbon::create($filters['year'], $filters['month'], $w['end'], 23, 59, 59, $tz);

                $count = PreOrder::whereBetween('created_at', [$wStart->utc(), $wEnd->utc()])->count();
                $val = (float) PreOrder::where('status', '!=', 'dibatalkan')
                    ->whereBetween('created_at', [$wStart->utc(), $wEnd->utc()])
                    ->sum('total_amount');

                $timelineLabels[] = $w['label'];
                $timelineOrders[] = $count;
                $timelineValues[] = $val;
            }
        } else {
            // Rentang khusus: agregasi per hari atau per interval jika panjang
            $diffDays = $periodStart->diffInDays($periodEnd) ?: 1;
            if ($diffDays <= 31) {
                $cursor = $periodStart->copy();
                while ($cursor->lte($periodEnd)) {
                    $dayStart = $cursor->copy()->startOfDay();
                    $dayEnd = $cursor->copy()->endOfDay();

                    $count = PreOrder::whereBetween('created_at', [$dayStart->utc(), $dayEnd->utc()])->count();
                    $val = (float) PreOrder::where('status', '!=', 'dibatalkan')
                        ->whereBetween('created_at', [$dayStart->utc(), $dayEnd->utc()])
                        ->sum('total_amount');

                    $timelineLabels[] = $cursor->format('d/m');
                    $timelineOrders[] = $count;
                    $timelineValues[] = $val;
                    $cursor->addDay();
                }
            } else {
                // Interval mingguan / bulanan jika > 31 hari
                $cursor = $periodStart->copy();
                while ($cursor->lte($periodEnd)) {
                    $chunkEnd = $cursor->copy()->addDays(6)->endOfDay();
                    if ($chunkEnd->gt($periodEnd)) {
                        $chunkEnd = $periodEnd->copy()->endOfDay();
                    }

                    $count = PreOrder::whereBetween('created_at', [$cursor->utc(), $chunkEnd->utc()])->count();
                    $val = (float) PreOrder::where('status', '!=', 'dibatalkan')
                        ->whereBetween('created_at', [$cursor->utc(), $chunkEnd->utc()])
                        ->sum('total_amount');

                    $timelineLabels[] = $cursor->format('d M') . ' - ' . $chunkEnd->format('d M');
                    $timelineOrders[] = $count;
                    $timelineValues[] = $val;

                    $cursor->addDays(7)->startOfDay();
                }
            }
        }

        // -------------------------------------------------------------
        // B. Diagram Donut — Distribusi Status Pesanan dalam Periode
        // -------------------------------------------------------------
        // Kelompokkan:
        // 1. Menunggu: menunggu_konfirmasi, menunggu_pembayaran_dp, menunggu_verifikasi_dp
        // 2. Aktif: pesanan_aktif, dikonfirmasi, tanam_berjalan, siap_panen, dalam_pengiriman
        // 3. Selesai: selesai
        // 4. Dibatalkan/Ditolak: dibatalkan
        $periodOrders = PreOrder::whereBetween('created_at', [$periodStart, $periodEnd])->get();
        $totalPeriodOrders = $periodOrders->count();

        $statusPending = $periodOrders->whereIn('status', ['menunggu_konfirmasi', 'menunggu_pembayaran_dp', 'menunggu_verifikasi_dp'])->count();
        $statusActive = $periodOrders->whereIn('status', ['pesanan_aktif', 'dikonfirmasi', 'tanam_berjalan', 'siap_panen', 'dalam_pengiriman'])->count();
        $statusCompleted = $periodOrders->where('status', 'selesai')->count();
        $statusCancelled = $periodOrders->where('status', 'dibatalkan')->count();

        $donutData = [
            'labels' => ['Menunggu', 'Aktif Berjalan', 'Selesai', 'Dibatalkan / Ditolak'],
            'counts' => [$statusPending, $statusActive, $statusCompleted, $statusCancelled],
            'total' => $totalPeriodOrders,
        ];

        // -------------------------------------------------------------
        // D. Grafik Batang Horizontal — Top 5 Komoditas Terbanyak Dipesan (kg)
        // Gabungkan pesanan dari batch berbeda untuk listing komoditas yang sama
        // -------------------------------------------------------------
        $topCommoditiesRaw = PreOrder::whereBetween('pre_orders.created_at', [$periodStart, $periodEnd])
            ->where('pre_orders.status', '!=', 'dibatalkan')
            ->join('commodity_listings', 'pre_orders.commodity_listing_id', '=', 'commodity_listings.id')
            ->select(
                'commodity_listings.commodity_name',
                DB::raw('SUM(pre_orders.quantity_kg) as total_kg'),
                DB::raw('SUM(pre_orders.total_amount) as total_revenue')
            )
            ->groupBy('commodity_listings.id', 'commodity_listings.commodity_name')
            ->orderByDesc('total_kg')
            ->limit(5)
            ->get();

        $topCommodityLabels = [];
        $topCommodityKg = [];
        foreach ($topCommoditiesRaw as $tc) {
            $topCommodityLabels[] = $tc->commodity_name;
            $topCommodityKg[] = (float) $tc->total_kg;
        }

        return [
            'timeline_labels' => $timelineLabels,
            'timeline_orders' => $timelineOrders,
            'timeline_values' => $timelineValues,
            'donut' => $donutData,
            'top_commodities' => [
                'labels' => $topCommodityLabels,
                'kg' => $topCommodityKg,
            ],
            'has_orders' => $totalPeriodOrders > 0,
            'has_valid_orders' => array_sum($timelineOrders) > 0,
            'has_commodities' => count($topCommodityLabels) > 0,
        ];
    }

    /**
     * Get orders filtered by period
     */
    private function getFilteredOrders($periodStart, $periodEnd)
    {
        return PreOrder::with(['commodityListing', 'farmer', 'buyer', 'harvestBatch'])
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->latest()
            ->get();
    }

    /**
     * Generate PDF filename based on filters
     */
    private function generateReportFilename(array $filters): string
    {
        $typeMap = [
            'keseluruhan' => 'Keseluruhan',
            'pesanan' => 'Transaksi',
            'pembayaran' => 'Pembayaran',
            'komoditas' => 'Komoditas',
            'pengguna' => 'Pengguna',
        ];
        $type = $typeMap[$filters['report_type']] ?? 'Keseluruhan';

        switch ($filters['period_type']) {
            case 'tahunan':
                return "TaniLink-Laporan-{$type}-Tahunan-{$filters['year']}.pdf";
            case 'rentang':
                $s = $filters['start_date'] ?? 'awal';
                $e = $filters['end_date'] ?? 'akhir';
                return "TaniLink-Laporan-{$type}-{$s}-sd-{$e}.pdf";
            default:
                $m = str_pad($filters['month'], 2, '0', STR_PAD_LEFT);
                return "TaniLink-Laporan-{$type}-Bulanan-{$filters['year']}-{$m}.pdf";
        }
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

    /**
     * Lihat Detail Riwayat Pesanan (Petani / Konsumen)
     */
    public function showOrderHistory($id)
    {
        $user = Auth::user();
        $order = PreOrder::with([
            'commodityListing',
            'farmer',
            'buyer',
            'harvestBatch.plantingProgress',
            'deliveryTracking',
            'activities.user'
        ])->findOrFail($id);

        // Otorisasi: hanya pemilik (petani / pembeli) atau admin yang boleh melihat
        if ($user->id !== $order->farmer_id && $user->id !== $order->buyer_id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat riwayat pesanan ini.');
        }

        $role = $user->id === $order->farmer_id ? 'petani' : 'pembeli';

        return view('tanilink.orders.history_detail', compact('order', 'user', 'role'));
    }

    /**
     * Unduh Laporan Riwayat Pesanan dalam format PDF
     */
    public function downloadOrderPdf($id)
    {
        $user = Auth::user();
        $order = PreOrder::with([
            'commodityListing',
            'farmer',
            'buyer',
            'harvestBatch.plantingProgress',
            'deliveryTracking',
            'activities.user'
        ])->findOrFail($id);

        // Otorisasi: hanya pemilik atau admin
        if ($user->id !== $order->farmer_id && $user->id !== $order->buyer_id && $user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki hak akses untuk mengunduh laporan pesanan ini.');
        }

        $pdf = Pdf::loadView('tanilink.pdf.order_history', compact('order', 'user'))
                  ->setPaper('a4', 'portrait');

        $filename = 'TaniLink-Riwayat-' . str_replace(['/', '\\'], '-', $order->order_code) . '.pdf';

        return $pdf->download($filename);
    }
}
