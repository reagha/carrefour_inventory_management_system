<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable =['supplier_id', 'procurement_manager_id', 'total_amount', 'status', 'received_by', 'received_at'];
    protected $casts = ['received_at' => 'datetime'];

    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function procurementManager() { return $this->belongsTo(User::class, 'procurement_manager_id'); }
    public function receivedBy() { return $this->belongsTo(User::class, 'received_by'); }
    public function items() { return $this->hasMany(PurchaseOrderItem::class); }
}
