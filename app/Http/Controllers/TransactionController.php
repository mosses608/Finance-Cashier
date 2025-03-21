<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //
    public function transactions(){
        $products = Product::orderBy('item_name','asc')->get();
        $stocks = Stock::all();
        $stores = Store::all(); 
        return view('inc.sales', compact('products', 'stocks', 'stores'));
    }

    public function storeTransaction(Request $request){
        $transactionDetails = $request->validate([
            'product_item_id' => 'required|integer',
            'stockout_quantity' => 'required|integer',
            'customer_name' => 'nullable|string|max:255',
            'selling_price' => 'required|integer',
            'stock_out_mode' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        try{
            Transaction::create($transactionDetails);

            return redirect()->back()->with('success_msg','Transaction complete successfully!');
        }catch(\Throwable $th){
            return $th->getMessage();
        }
    }
}
