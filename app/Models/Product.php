<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function scopeFilter($query, array $filters){
        if($filters['search'] ?? false){
            $query->where('item_name', 'like', '%' . request('search') . '%')
            ->orwhere('item_specs', 'like' , '%' . request('search') . '%')
            ->orwhere('item_category' , 'like' , '%' . request('search') . '%')
            ->orwhere('item_quantity_unit' , 'like' , '%' . request('search') . '%');
        }
    }

    protected $fillable = [
        'item_name','item_specs','item_quantity_unit','item_category','item_pic','item_prefix','store_id'
    ];

    public static function find($id){
        $products = self::all();

        foreach ($products as $product) {
            if($product['id'] == $id){
                return $product;
            }
        }
    }

    public function stock()
    {
        return $this->hasOne(Stock::class, 'storage_item_id', 'id');
    }
}
