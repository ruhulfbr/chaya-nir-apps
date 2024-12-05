<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'category_id'     => $this->category_id,
            'title'           => $this->title,
            'description'     => $this->description,
            'amount'          => $this->amount,
            'receipt'         => $this->receipt,
            'spent_at'        => date('Y-m-d', strtotime($this->spent_at)),
            'spent_by'        => $this->spent_by,
            'spent_by_member' => $this->spentBy,
            'category'        => $this->category,
            'created_at'      => $this->created_at?->format('Y-m-d H:i:s') ?? "",
            'updated_at'      => $this->updated_at?->format('Y-m-d H:i:s') ?? ""
        ];
    }
}
