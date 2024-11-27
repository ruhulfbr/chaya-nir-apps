<?php

namespace App\Http\Resources\Deposit;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'date' => $this->date,
            'amount' => $this->amount,
            'comment' => $this->comment,
            'slip' => $this->slip,
            'method' => $this->method,
            'deposit_at' => $this->deposit_at,
            'member' => $this->member,
            'received_by' => $this->receivedBy,
        ];
    }
}
