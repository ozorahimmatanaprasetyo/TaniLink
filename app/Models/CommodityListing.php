<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommodityListing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'commodity_name',
        'category',
        'variety',
        'grade',
        'land_area_m2',
        'estimated_harvest_kg',
        'available_kg',
        'price_per_kg',
        'harvest_estimated_date',
        'location',
        'latitude',
        'longitude',
        'image_url',
        'description',
        'status',
    ];

    protected $casts = [
        'harvest_estimated_date' => 'date',
        'price_per_kg' => 'decimal:2',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preOrders()
    {
        return $this->hasMany(PreOrder::class, 'commodity_listing_id');
    }

    public function harvestBatches()
    {
        return $this->hasMany(HarvestBatch::class, 'commodity_listing_id');
    }

    public function activeBatch()
    {
        return $this->hasOne(HarvestBatch::class, 'commodity_listing_id')->where('status', '!=', 'selesai')->latest();
    }

    public function getGradeLabelAttribute()
    {
        $map = [
            'Grade A' => 'Kualitas Pilihan',
            'Grade A (Ekspor & Supermarket)' => 'Kualitas Pilihan',
            'Grade B' => 'Kualitas Standar',
            'Grade B (Pasar Swalayan & Resto)' => 'Kualitas Standar',
            'Grade C' => 'Kualitas Campuran',
            'Grade C (Lokal Pasar)' => 'Kualitas Campuran',
            'Kualitas Pilihan' => 'Kualitas Pilihan',
            'Kualitas Standar' => 'Kualitas Standar',
            'Kualitas Campuran' => 'Kualitas Campuran',
            'Belum Ditentukan' => 'Belum Ditentukan',
        ];
        return $map[$this->grade] ?? ($this->grade ?: 'Belum Ditentukan');
    }

    public function getResolvedImageUrlAttribute(): string
    {
        $raw = trim((string) $this->image_url);

        // Jika sudah berupa path file lokal yang valid di folder public
        if ($raw !== '' && !str_starts_with($raw, 'http')) {
            $clean = ltrim($raw, '/');
            if (file_exists(public_path($clean))) {
                return asset($clean);
            }
            if (str_starts_with($clean, 'storage/') && file_exists(storage_path('app/public/' . substr($clean, 8)))) {
                return asset($clean);
            }
        }

        // 1. Pemetaan berdasarkan ID komoditas spesifik dari database seeder
        $idMap = [
            27 => 'images/products/cabai-rawit.jpg',      // Cabai Rawit Merah Ori 212
            28 => 'images/products/cabai-keriting.jpg',   // Cabai Merah Keriting Kastilo
            29 => 'images/products/bawang-merah.jpg',    // Bawang Merah Bima Brebes
            30 => 'images/products/tomat-ceri.jpg',       // Tomat Ceri Hidroponik Ruby
            31 => 'images/products/kentang-granola.jpg',  // Kentang Granola Lembang Super
            32 => 'images/products/wortel.jpg',           // Wortel Brastagi Manis
            33 => 'images/products/jagung-manis.jpg',     // Jagung Manis Bonanza F1
            34 => 'images/products/kubis.jpg',            // Kubis Bulat Dataran Tinggi
            35 => 'images/products/bawang-putih.jpg',    // Bawang Putih Tunggal Lanang
            36 => 'images/products/jahe-merah.jpg',       // Jahe Merah Gajah Unggulan
            37 => 'images/products/brokoli.jpg',          // Brokoli Hijau Dataran Tinggi
            38 => 'images/products/labu-madu.jpg',        // Labu Madu Butternut Squash
        ];

        if (isset($this->id) && isset($idMap[$this->id])) {
            $path = $idMap[$this->id];
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // 2. Pemetaan ketat dan cerdas berdasarkan nama komoditas dan varietas (tanpa mencampuradukkan kategori)
        $nameLower = strtolower($this->commodity_name . ' ' . ($this->variety ?? ''));

        if (str_contains($nameLower, 'cabai rawit') || str_contains($nameLower, 'cabe rawit')) {
            return asset('images/products/cabai-rawit.jpg');
        } elseif (str_contains($nameLower, 'cabai merah') || str_contains($nameLower, 'cabe merah') || str_contains($nameLower, 'cabai keriting') || str_contains($nameLower, 'cabe keriting')) {
            return asset('images/products/cabai-keriting.jpg');
        } elseif (str_contains($nameLower, 'bawang merah')) {
            return asset('images/products/bawang-merah.jpg');
        } elseif (str_contains($nameLower, 'bawang putih') || str_contains($nameLower, 'bawang lanang')) {
            return asset('images/products/bawang-putih.jpg');
        } elseif (str_contains($nameLower, 'tomat')) {
            return asset('images/products/tomat-ceri.jpg');
        } elseif (str_contains($nameLower, 'kentang')) {
            return asset('images/products/kentang-granola.jpg');
        } elseif (str_contains($nameLower, 'wortel')) {
            return asset('images/products/wortel.jpg');
        } elseif (str_contains($nameLower, 'jagung')) {
            return asset('images/products/jagung-manis.jpg');
        } elseif (str_contains($nameLower, 'kubis') || str_contains($nameLower, 'kol ')) {
            return asset('images/products/kubis.jpg');
        } elseif (str_contains($nameLower, 'jahe')) {
            return asset('images/products/jahe-merah.jpg');
        } elseif (str_contains($nameLower, 'brokoli')) {
            return asset('images/products/brokoli.jpg');
        } elseif (str_contains($nameLower, 'labu')) {
            return asset('images/products/labu-madu.jpg');
        } elseif (str_contains($nameLower, 'mentimun') || str_contains($nameLower, 'timun')) {
            return asset('images/products/mentimun.jpg');
        } elseif (str_contains($nameLower, 'terong')) {
            return asset('images/products/terong.jpg');
        } elseif (str_contains($nameLower, 'beras') || str_contains($nameLower, 'padi')) {
            return asset('images/products/beras.jpg');
        } elseif (str_contains($nameLower, 'cabai') || str_contains($nameLower, 'cabe')) {
            return asset('images/products/cabai-keriting.jpg');
        }

        // 3. Fallback jika user mengunggah URL eksternal kustom
        if ($raw !== '' && str_starts_with($raw, 'http')) {
            return $raw;
        }

        // 4. Placeholder netral sesuai kategori komoditas
        $cat = strtolower((string) $this->category);
        if (str_contains($cat, 'sayur')) {
            return asset('images/products/placeholder-sayuran.jpg');
        } elseif (str_contains($cat, 'buah')) {
            return asset('images/products/placeholder-buah.jpg');
        } elseif (str_contains($cat, 'bumbu') || str_contains($cat, 'rempah')) {
            return asset('images/products/placeholder-bumbu.jpg');
        } elseif (str_contains($cat, 'padi') || str_contains($cat, 'palawija')) {
            return asset('images/products/placeholder-palawija.jpg');
        }

        return asset('images/products/default-vegetable.jpg');
    }
}


