<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function detail() {
        return $this->hasOne(ProductDetail::class);
    }
    
    public function warehouses() {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')
                    ->withPivot('quantity');
    }
}
