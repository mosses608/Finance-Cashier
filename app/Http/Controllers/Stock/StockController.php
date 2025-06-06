<?php

namespace App\Http\Controllers\Stock;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    //
    public function stock(Request $request)
    {
        $stockDetails = $request->validate([
            'storage_item_id' => 'required|integer',
            'quantity_in' => 'required|integer',
            'item_price' => 'required|integer',
            'remarks' => 'nullable|string|max:255',
        ]);

        $existsingProductName = Stock::where('storage_item_id', $request->input('storage_item_id'))->first();

        if ($existsingProductName) {
            return redirect()->back('error_msg', 'Stock details exists!');
        }

        Stock::create($stockDetails);
        return redirect()->back()->with('success_msg', 'Product imported successfully!');
    }

    public function stockInMethod()
    {
        $products = DB::table('products AS PR')
            ->join('stores AS ST', 'PR.store_id', '=', 'ST.id')
            // ->join('stocks AS STK', 'PR.id', '=', 'STK.storage_item_id')
            ->select([
                'PR.name AS productName',
                'PR.sku AS sku',
                'PR.cost_price AS cost_price',
                'PR.selling_price AS selling_price',
                'ST.store_name AS storeName',
                'PR.id AS id',
                // DB::raw('SUM(STK.quantity_total) AS stockQuantity')
            ])
            ->where('PR.soft_delete', 0)
            ->where('ST.soft_delete', 0)
            // ->groupBy('PR.id', 'PR.name', 'PR.sku', 'PR.cost_price', 'PR.selling_price', 'ST.store_name', 'ST.id')
            ->get();

        $stocks = DB::table('stocks')->select('storage_item_id', 'quantity_total')->where('soft_delete', 0)->get();

        // dd($stocks);

        return view('inc.stock-in', compact('products', 'stocks'));
    }

    public function stockInQuantity(Request $request)
    {
        $request->validate([
            'auto_id' => 'required',
            'quantity' => 'required',
            'seling_price' => 'required',
            // 'available_quantity' => 'nullable',
        ]);

        $availableQuantity = DB::table('stocks')
            ->select([
                'quantity_total'
            ])
            ->where('storage_item_id', $request->auto_id)->value('quantity_total');

        $stockFinder = DB::table('stocks')
            ->where('storage_item_id', $request->auto_id)->first();

        if ($stockFinder) {

            DB::table('stocks')->where('storage_item_id', $request->auto_id)->update(
                [
                    'quantity_in' => $request->quantity + $availableQuantity,
                    'quantity_out' => $stockFinder->quantity_out,
                    'quantity_total' => $request->quantity + $availableQuantity - $stockFinder->quantity_out,
                ]
            );
        }

        if (!$stockFinder) {
            DB::table('stocks')->where('storage_item_id', $request->auto_id)->insert(
                [
                    'storage_item_id' => $request->auto_id,
                    'item_price' => $request->seling_price,
                    'quantity_in' => $request->quantity,
                    'quantity_total' => $request->quantity,
                ]
            );
            // DB::table('stocks')->where('storage_item_id', $request->auto_id)->update(['quantity_in' => $request->quantity]);
        }

        return redirect()->back()->with('success_msg', 'Data saved successfully!');
    }

    public function stockOut()
    {
        $stockProducts = DB::table('products as PR')
            ->join('stocks AS STK', 'PR.id', '=', 'STK.storage_item_id')
            ->select([
                'PR.id AS productId',
                'PR.name AS productName',
                'STK.quantity_total AS availableQuantity',
                'STK.item_price AS sellingPrice',
            ])
            ->where('PR.soft_delete', 0)
            ->where('STK.soft_delete', 0)
            ->orderBy('PR.name', 'ASC')
            ->get();

        $customers = DB::table('customer')
            ->select('id', 'name')
            ->where('soft_delete', 0)
            ->orderBy('name','ASC')
            ->get();

        // dd($customers);
        return view('inc.stock-out', compact('stockProducts', 'customers'));
    }
}
