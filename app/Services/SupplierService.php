<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Supplier;

class SupplierService
{
    public function getSupplierByName(string $name): ?Supplier
    {
        return Supplier::query()
            ->where('name', $name)
            ->first();
    }
}
