<?php

namespace App\Http\Resources\Expense;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'amount' => $this->amount,
            'receipt' => $this->receipt,
            'comment' => $this->comment,
            'spent_at' => $this->spent_at,
            'spent_by' => $this->spentBy
        ];
    }
}
