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
                'name'         => 'Cuci Reguler',
                'description'  => 'Cuci biasa tanpa setrika, estimasi 2-3 hari',
                'price_per_kg' => 5000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Cuci + Setrika',
                'description'  => 'Cuci dan setrika, estimasi 2-3 hari',
                'price_per_kg' => 7000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Express (Same Day)',
                'description'  => 'Selesai di hari yang sama (order sebelum jam 10)',
                'price_per_kg' => 12000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Dry Cleaning',
                'description'  => 'Dry cleaning untuk pakaian premium',
                'price_per_kg' => 25000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Setrika Saja',
                'description'  => 'Layanan setrika tanpa cuci',
                'price_per_kg' => 4000,
                'is_active'    => true,
            ],
            [
                'name'         => 'Cuci Sepatu',
                'description'  => 'Cuci sepatu premium, per pasang',
                'price_per_kg' => 35000,
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
