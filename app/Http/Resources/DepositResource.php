<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'slip' => $this->slip,
            'method' => $this->method,
            'deposit_at' => date('Y-m-d', $this->deposit_at),
            'member' => $this->member,
            'received_by' => $this->receivedBy ?? null,
            'created_at' => date('Y-m-d H:i:s', strtotime($this->created_at)),
            'updated_at' => date('Y-m-d H:i:s', strtotime($this->updated_at)),
        ];
    }
}
