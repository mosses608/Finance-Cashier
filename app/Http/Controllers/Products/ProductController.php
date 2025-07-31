<?php

namespace App\Http\Controllers\Products;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    //
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string',
            'description' => 'nullable|string|max:255',
            // 'quantity' => 'nullable|integer',
            'cost_price' => 'nullable|integer',
            'selling_price' => 'nullable|integer',
            'store_id' => 'nullable|integer',
            'item_pic' => 'nullable|image|max:2048',
            'serial_no' => 'nullable|string',
        ]);

        $fileStore = null;

        if ($request->hasFile('item_pic')) {
            $fileStore = $request->file('item_pic')->store('product_pics', 'public');
        }

        $companyId = Auth::user()->company_id;

        $existingProduct = DB::table('products')
            ->where('company_id', $companyId)
            ->where(function($query) use($request){
                $query->where('serial_no', $request->serial_no)
                ->orWhere('name', $request->name);
            })
            ->first();

        if ($existingProduct) {
            return redirect()->back()->with('error_msg', 'Product' . ' ' . 'with name' . ' ' . $request->name . ' ' .  'exists!');
        }

        DB::table('products')->insert([
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            // 'quantity' => $request->quantity,
            'cost_price' => $request->cost_price,
            'selling_price' => $request->selling_price,
            'store_id' => $request->store_id,
            'item_pic' => $fileStore,
            'company_id' => $companyId,
            'serial_no' => $request->serial_no,
        ]);

        return redirect()->back()->with('success_msg', 'Product registered successfully!');
    }

    public function downloadExcelFile()
    {
        $filename = "product_upload_template.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate",
            "Expires" => "0"
        ];

        $columns = ['name','serial_no', 'sku', 'description', 'cost_price', 'selling_price', 'store_name'];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importProductFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));

            return redirect()->back()->with('success_msg', 'Excel file uploaded successfully!');
        } catch (\Exception $e) {
            Log::error('Import error: ' . $e->getMessage());
            return redirect()->back()->with('error_msg', 'Import failed: ' . $e->getMessage());
        }
    }

    public function destroyProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        $productId = $request->product_id;

        $productExistsInDb = DB::table('products')->where('id', $productId)->first();

        if (!$productExistsInDb) {
            return redirect()->back()->with('error_msg', 'Product with Id' . ' ' . $productId . ' ' . 'does not exists!');
        }

        if ($productExistsInDb) {
            DB::table('products')->where('id', $productId)->update([
                'soft_delete' => 1,
            ]);
        }

        return redirect()->back()->with('success_msg', 'Product deleted successfully!');
    }

    public function singleProduct($id)
    {
        $transactions = Transaction::where('product_item_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        $stores = Store::all();
        $product = Product::find($id);
        $stocks = Stock::where('storage_item_id', $id)->get();
        return view('inc.single-product', compact('product', 'stores', 'stocks', 'transactions'));
    }
}
