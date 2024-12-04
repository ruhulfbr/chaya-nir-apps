<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expense extends Model
{
    protected $fillable = ['category_id', 'title', 'description', 'amount', 'receipt', 'spent_by', 'spent_at', 'created_by'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function spentBy(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id', 'spent_by');
    }

}
