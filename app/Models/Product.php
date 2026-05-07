<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable =['sku', 'name', 'supplier_id', 'unit_cost', 'quantity_in_warehouse', 'reorder_level'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function purchaseOrderItems() { return $this->hasMany(PurchaseOrderItem::class); }
    public function branchRequestItems() { return $this->hasMany(BranchRequestItem::class); }
}
