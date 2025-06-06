<?php
namespace App\Imports;

use App\Models\Product;
use App\Models\Store;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProductsImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public function model(array $row)
    {
        $store = Store::where('store_name', $row['store_name'])->first();

        if($row['name'] === null || $row['sku'] === null || $row['description'] === null || $row['cost_price'] === null || $row['selling_price'] === null || $row['store_id'] === null){
            return redirect()->back()->with('error_msg','Some filed are missing data!');
        }

        return new Product([
            'name'           => $row['name'] ?? '',
            'sku'            => $row['sku'] ?? '',
            'description'    => $row['description'] ?? '',
            'cost_price'     => $row['cost_price'] ?? 0,
            'selling_price'  => $row['selling_price'] ?? 0,
            'store_id'       => $store ? $store->id : 1,
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
