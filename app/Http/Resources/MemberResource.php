<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'phone'                   => $this->phone,
            'status'                  => $this->status,
            'total_deposit'           => $this->deposits_sum_amount ?? 0,
            'total_deposit_formatted' => number_format($this->deposits_sum_amount ?? 0),
            'created_at'              => $this->created_at?->format('Y-m-d H:i:s') ?? "",
            'updated_at'              => $this->updated_at?->format('Y-m-d H:i:s') ?? ""
        ];
    }
}
