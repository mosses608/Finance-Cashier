<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name','sku','description','cost_price','selling_price','store_id','item_pic','soft_delete','company_id','serial_no',
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
