<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchRequestItem extends Model
{
    protected $fillable =['branch_request_id', 'product_id', 'quantity'];

    public function branchRequest() { return $this->belongsTo(BranchRequest::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
