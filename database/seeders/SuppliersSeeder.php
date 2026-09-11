<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->getSuppliersData() as $supplierData) {
            Supplier::insertOrIgnore([
                'name' => $supplierData['name'],
            ]);
        }
    }

    public function getSuppliersData(): array
    {
        return [
            [
                'name' => 'supplier-a',
            ],
            [
                'name' => 'supplier-b',
            ],
        ];
    }
}
