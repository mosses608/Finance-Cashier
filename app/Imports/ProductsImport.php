<?php

namespace App\Imports;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProductsImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public function model(array $row)
    {
        $store = Store::where('store_name', $row['store_name'])->first();
        
        $companyId = Auth::user()->company_id;
            
        if (
            empty($row['name']) ||
            empty($row['sku']) ||
            empty($row['description']) ||
            empty($row['cost_price']) ||
            empty($row['selling_price']) ||
            empty($row['serial_no']) ||
            !$store
        ) {
            return null;
        }

        return new Product([
            'name'           => $row['name'],
            'sku'            => $row['sku'],
            'description'    => $row['description'],
            'cost_price'     => $row['cost_price'],
            'selling_price'  => $row['selling_price'],
            'store_id'       => $store->id,
            'company_id' => $companyId,
            'serial_no' => $row['serial_no'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => '"',
            'input_encoding' => 'UTF-8',
        ];
    }
}
