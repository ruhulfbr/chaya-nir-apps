<?php

namespace App\Http\Resources\Member;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'phone'         => $this->phone,
            'photo'         => $this->photo ?? 'https://placehold.co/70x70.png',
            'status'        => $this->status,
            'total_deposit' => $this->deposits_sum_amount ?? 0
        ];
    }
}
