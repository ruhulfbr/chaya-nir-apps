<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = ['title', 'description', 'amount', 'receipt', 'comment', 'spent_by', 'spent_at', 'created_by'];


    public function spentBy(): HasOne
    {
        return $this->hasOne(Member::class, 'id', 'spent_by');
    }

}
