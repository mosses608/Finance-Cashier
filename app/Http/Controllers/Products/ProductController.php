<?php

namespace App\Http\Controllers\Products;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    //
    public function storeProduct(Request $request){
        $productDetails = $request->validate([
            'item_name' => 'required|string|max:255',
            'item_specs' => 'nullable|string|max:255',
            'item_quantity_unit' => 'nullable|string|max:255',
            'item_category' => 'nullable|string|max:255',
            'store_id' => 'required|integer',
            'item_prefix' => 'required|string',
            'item_pic' => 'nullable|image|max:2048',
        ]);

        if($request->hasFile('item_pic')){
            $productDetails['item_pic'] = $request->file('item_pic')->store('product_pics','public');
        }

        $existingProduct = Product::where('item_name', $request->input('item_name'))->first();

        if($existingProduct){
            return redirect()->back()->with('error_msg','Product exists!');
        }

        Product::create($productDetails);

        return redirect()->back()->with('success_msg','Product registered successfully!');
    }

    public function singleProduct($id){
        $transactions = Transaction::where('product_item_id', $id)
        ->orderBy('created_at', 'asc')
        ->get();
        $stores = Store::all();
        $product = Product::find($id);
        $stocks = Stock::where('storage_item_id', $id)->get();
        return view('inc.single-product', compact('product','stores','stocks','transactions'));
    }
}
