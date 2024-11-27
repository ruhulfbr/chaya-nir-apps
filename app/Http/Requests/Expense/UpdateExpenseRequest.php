<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => ['string', 'required', 'max:255'],
            'description' => ['string', 'nullable'],
            'amount' => ['numeric', 'required'],
            'receipt' => ['string', 'nullable', 'max:255'],
            'comment' => ['string', 'required', 'nullable'],
            'spent_by' => ['nullable', 'exists:members,id'],
        ];
    }
}
