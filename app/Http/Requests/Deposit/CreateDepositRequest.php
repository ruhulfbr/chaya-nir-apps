<?php

namespace App\Http\Requests\Deposit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateDepositRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'received_by' => ['nullable', 'exists:members,id'],
            'amount' => ['numeric', 'required'],
            'comment' => ['string', 'nullable'],
            'slip' => ['string', 'nullable', 'max:255'],
            'method' => ['string', 'nullable', 'max:100'],
            'deposit_at' => ['date', 'required'],
        ];
    }
}
