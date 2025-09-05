<?php

namespace App\Http\Controllers\Products;

use Carbon\Carbon;
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
        try {
            $request->validate([
                // ALL TYPES VALIDATION
                'product_type' => 'required|string',

                // SERVICE VALIDATION
                'service_name' => 'nullable|string',
                'amount_service' => 'nullable|numeric',
                'quantity_service' => 'nullable|integer',
                'category_service' => 'nullable|string',
                'description_service' => 'nullable|string',

                // GOODS VALIDATION
                'name' => 'nullable|string',
                'sku' => 'nullable|string',
                'description' => 'nullable|string',
                'cost_price' => 'nullable|integer',
                'selling_price' => 'nullable|integer',
                'store_id' => 'nullable|integer',
                'item_pic' => 'nullable|file',
                'serial_no' => 'nullable|string',
            ]);
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }


        $companyId = Auth::user()->company_id;

        if ($request->has('product_type') && $request->product_type === 'is_goods') {

            $fileStore = null;

            if ($request->hasFile('item_pic')) {
                $fileStore = $request->file('item_pic')->store('product_pics', 'public');
            }

            $existingProduct = DB::table('products')
                ->where('company_id', $companyId)
                ->where(function ($query) use ($request) {
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
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->back()->with('success_msg', 'Product registered successfully!');
        }

        if ($request->has('product_type') && $request->product_type === 'is_service') {
            $existingSevice = DB::table('service')
                ->where('name', $request->service_name)
                ->where('company_id', $companyId)
                ->where('soft_delete', 0)
                ->first();

            if ($existingSevice) {
                return redirect()->back()->with('error_msg', 'Service' . ' ' . 'with name' . ' ' . $request->service_name . ' ' .  'exists!');
            }

            DB::table('service')->insert([
                'name' => $request->service_name,
                'description' => $request->description_service,
                'price' => $request->amount_service,
                'category' => $request->category_service,
                'created_by' => Auth::user()->user_id,
                'quantity' => $request->quantity_service,
                'company_id' => $companyId,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            return redirect()->route('service.page')->with('success_msg', 'Service registered successfully!');
        }
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

        $columns = ['name', 'serial_no', 'sku', 'description', 'cost_price', 'selling_price', 'store_name'];

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
