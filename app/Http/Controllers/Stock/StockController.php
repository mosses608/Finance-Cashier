<?php

namespace App\Http\Controllers\Stock;

use App\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    //
    public function stock(Request $request){
        $stockDetails = $request->validate([
            'storage_item_id' => 'required|integer',
            'quantity_in' => 'required|integer',
            'item_price' => 'required|integer',
            'remarks' => 'nullable|string|max:255',
        ]);

        $existsingProductName = Stock::where('storage_item_id', $request->input('storage_item_id'))->first();

        if($existsingProductName){
            return redirect()->back('error_msg','Stock details exists!');
        }

        Stock::create($stockDetails);
        return redirect()->back()->with('success_msg','Product imported successfully!');
    }
}
