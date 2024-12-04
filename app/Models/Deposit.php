<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Deposit extends Model
{
    protected $fillable = ['member_id', 'received_by', 'amount', 'comment', 'slip', 'deposit_at', 'created_by'];


    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
    }

    public function receivedBy(): HasOne
    {
        return $this->hasOne(Member::class, 'id', 'received_by');
    }

}
