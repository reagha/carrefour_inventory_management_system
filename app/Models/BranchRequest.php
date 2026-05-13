<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchRequest extends Model
{
    protected $fillable = [
        'branch_id',
        'branch_manager_id',
        'status',
        'dispatched_by',
        'dispatched_at'
    ];

    protected $casts = [
        'dispatched_at' => 'datetime'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // ✅ FIXED: keep camelCase relationship name
    public function branchManager()
    {
        return $this->belongsTo(User::class, 'branch_manager_id');
    }

    public function dispatchedBy()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }

    public function manager()
    {
        return $this->branchManager();
    }

    public function items()
    {
        return $this->hasMany(BranchRequestItem::class);
    }
}