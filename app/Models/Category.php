<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name'];


    public function expenses(): hasMany
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

}
