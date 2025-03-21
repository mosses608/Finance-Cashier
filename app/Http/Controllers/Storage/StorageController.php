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
use App\Http\Controllers\Controller;

class StorageController extends Controller
{
    //
    public function storeManage(){
        $roles = Role::all();
        $departments = Department::all();
        $stores = Store::orderBy('store_name','asc')->get();
        $categories = Category::orderBy('name','asc')->get();
        $products = Product::orderBy('id','asc')->filter(request(['search']))->get();
        $stocks = Stock::all();
        $transactions = Transaction::all();
        return view('inc.store', compact('roles','departments','stores','categories','products','stocks','transactions'));
    }

    public function register(Request $request){
        $storeDetails = $request->validate([
            'store_name' => ['required', Rule::unique('stores','store_name')],
            'location' => 'nullable|string|max:255',
            'phone' => ['nullable', Rule::unique('stores','phone')],
        ]);

        $existingStore = Store::where('store_name', $request->input('store_name'))->first();

        if($existingStore){
            return redirect()->back()->with('Store exists!');
        }

        Store::create($storeDetails);

        return redirect()->back()->with('success_msg','Store registered successfully!');
    }
}
