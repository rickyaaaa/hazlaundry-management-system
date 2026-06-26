<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaundryService;

class LaundryServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name'         => 'Kemeja Pendek / Panjang',
                'description'  => 'Layanan cuci kemeja lengan pendek maupun panjang per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kemeja Anak',
                'description'  => 'Layanan cuci kemeja anak. Kemeja panjang: Rp 40.000',
                'price_per_kg' => 35000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Setel Baju Anak',
                'description'  => 'Layanan cuci satu setel baju anak',
                'price_per_kg' => 55000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kemeja Anak Panjang',
                'description'  => 'Layanan cuci kemeja anak lengan panjang',
                'price_per_kg' => 40000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kemeja Batik Pendek / Panjang',
                'description'  => 'Layanan cuci kemeja batik per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kaos Pendek / Panjang',
                'description'  => 'Layanan cuci kaos lengan pendek maupun panjang per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kaos Anak',
                'description'  => 'Layanan cuci kaos anak per pcs',
                'price_per_kg' => 25000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Rompi Dewasa',
                'description'  => 'Layanan cuci rompi dewasa per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Rompi Anak',
                'description'  => 'Layanan cuci rompi anak per pcs',
                'price_per_kg' => 35000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Blouse',
                'description'  => 'Layanan cuci blouse. Rentang harga Rp 38.000 - Rp 75.000',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Setel Blouse',
                'description'  => 'Layanan cuci satu setel blouse',
                'price_per_kg' => 75000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Celana Pendek / Panjang',
                'description'  => 'Layanan cuci celana pendek atau panjang per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Slack',
                'description'  => 'Layanan cuci celana slack per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Rok',
                'description'  => 'Layanan cuci rok per pcs',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jas (2 pcs)',
                'description'  => 'Layanan cuci satu setel jas isi 2 pcs',
                'price_per_kg' => 95000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jas (3 pcs)',
                'description'  => 'Layanan cuci satu setel jas isi 3 pcs',
                'price_per_kg' => 114000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Wedding Gown',
                'description'  => 'Layanan cuci gaun pengantin. Rentang harga Rp 350.000 - Rp 400.000',
                'price_per_kg' => 350000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Evening Gown',
                'description'  => 'Layanan cuci gaun pesta. Rentang harga Rp 120.000 - Rp 400.000',
                'price_per_kg' => 120000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Dress',
                'description'  => 'Layanan cuci dress. Rentang harga Rp 46.000 - Rp 85.000',
                'price_per_kg' => 46000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Long Dress',
                'description'  => 'Layanan cuci gaun panjang. Rentang harga Rp 60.000 - Rp 115.000',
                'price_per_kg' => 60000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Kebaya',
                'description'  => 'Layanan cuci kebaya. Rentang harga Rp 60.000 - Rp 130.000',
                'price_per_kg' => 60000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Setel Nasional',
                'description'  => 'Layanan cuci pakaian satu setel nasional',
                'price_per_kg' => 76000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Outter',
                'description'  => 'Layanan cuci outter. Rentang harga Rp 38.000 - Rp 46.000',
                'price_per_kg' => 38000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jaket',
                'description'  => 'Layanan cuci jaket biasa per pcs',
                'price_per_kg' => 45000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jaket Kulit',
                'description'  => 'Layanan cuci jaket kulit premium. Rentang harga Rp 125.000 - Rp 350.000',
                'price_per_kg' => 125000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jaket Bulu Angsa',
                'description'  => 'Layanan cuci jaket bulu angsa per pcs',
                'price_per_kg' => 100000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Sweater',
                'description'  => 'Layanan cuci sweater per pcs',
                'price_per_kg' => 40000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Jas & Blazer',
                'description'  => 'Layanan cuci jas/blazer satuan. Rentang harga Rp 47.000 - Rp 85.000',
                'price_per_kg' => 47000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Sepatu',
                'description'  => 'Layanan cuci sepatu per pasang',
                'price_per_kg' => 90000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Sepatu Kulit',
                'description'  => 'Layanan cuci sepatu kulit premium',
                'price_per_kg' => 150000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Sepatu Anak',
                'description'  => 'Layanan cuci sepatu anak per pasang',
                'price_per_kg' => 50000,
                'is_active'    => true,
            ],
        ];

        foreach ($services as $service) {
            LaundryService::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
