<?php

namespace App\Http\Controllers\Storage;

use App\Models\Role;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\Department;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class StorageController extends Controller
{
    //
    public function storeManage()
    {
        $roles = Role::all();
        $departments = Department::all();
        $stores = DB::table('stores')->select('*')->orderBy('store_name', 'ASC')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $products = Product::orderBy('id', 'asc')->get();
        $stocks = Stock::all();
        $transactions = Transaction::all();
        return view('inc.store', compact('roles', 'departments', 'stores', 'categories', 'products', 'stocks', 'transactions'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string',
            'city' => 'nullable|string|max:255',
            'location' => 'nullable|string',
            'store_keeper' => 'nullable|string',
            'phone' => 'nullable',
        ]);

        $existingStore = DB::table('stores')->where('store_name', $request->store_name)->first();

        if ($existingStore) {
            return redirect()->back()->with('error_msg', 'This store already exists');
        }

        // dd($existingStore);

        $data = Store::create([
            'store_name' => $request->store_name,
            'city' => $request->city,
            'location' => $request->location,
            'store_keeper' => $request->store_keeper,
            'phone' => $request->phone,
        ]);

        // dd($data);

        return redirect()->route('store.list')->with('success_msg', 'Store registered successfully!');
    }

    public function storePage()
    {
        $cities = DB::table('city')
            ->select('*')
            ->where('soft_delete', 0)
            ->get();
        // dd($cities);
        return view('inc.add-store', compact('cities'));
    }

    public function storeLists()
    {
        $stores = DB::table('stores AS ST')
            ->join('products AS PR', 'ST.id', '=', 'PR.store_id')
            ->select([
                'ST.id AS autoId',
                'ST.store_name AS store_name',
                'ST.city AS city',
                'ST.location AS location',
                'ST.store_keeper AS store_keeper',
                'ST.phone AS phone',
                DB::raw('COUNT(PR.id) AS totalItems'),
            ])
            ->where('ST.soft_delete', 0)
            ->groupBy(
                'ST.store_name',
                'ST.city',
                'ST.location',
                'ST.store_keeper',
                'ST.phone',
                'ST.id',
            )
            ->orderBy('ST.store_name', 'ASC')
            ->get();

            // dd($stores);

        return view('inc.store-list', compact(['stores']));
    }

    public function viewStore($encryptedStoreId)
    {
        try {
            $storeId = Crypt::decrypt($encryptedStoreId);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        $storageData = DB::table('products AS PR')
            ->select([
                'PR.name AS productName',
                'PR.sku AS sku',
                'PR.description AS description',
                'PR.cost_price AS costPrice',
                'PR.selling_price AS sellingPrice',
                'PR.id AS productAutoId',
            ])
            ->where('PR.store_id', $storeId)
            ->where('PR.soft_delete', 0)
            ->orderBy('PR.name', 'ASC')
            ->get();

        $productStocks = DB::table('stocks')
            ->select([
                'storage_item_id',
                'quantity_total',
            ])
            ->where('soft_delete', 0)
            ->get();

        $itemsCounter = $storageData->count();

        $storeName = DB::table('stores')->where('id', $storeId)->value('store_name');

        return view('inc.view-store', compact('storageData', 'storeName', 'itemsCounter', 'productStocks'));
    }
}
