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
            'comment' => ['required', 'string', 'max:5000'],
            'slip' => ['nullable', 'string', 'max:255'],
            'deposit_at' => ['required', 'date'],
        ];
    }
}
