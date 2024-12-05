<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'amount'             => $this->amount,
            'amount_formatted'   => number_format($this->amount),
            'comment'            => $this->comment,
            'slip'               => $this->slip,
            'deposit_at'         => date('Y-m-d', strtotime($this->deposit_at)),
            'member'             => $this->member,
            'member_id'          => $this->member_id,
            'received_by'        => $this?->receivedBy?->id,
            'received_by_member' => $this->receivedBy ?? null,
            'created_at'         => $this->created_at?->format('Y-m-d H:i:s') ?? "",
            'updated_at'         => $this->updated_at?->format('Y-m-d H:i:s') ?? ""
        ];
    }
}
