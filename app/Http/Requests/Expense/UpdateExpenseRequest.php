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
            'title'       => ['required', 'string', 'max:255'],
            'stair_no'    => ['required', 'string'],
            'category_id' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'amount'      => ['required', 'numeric'],
            'receipt'     => ['nullable', 'url', 'max:255'],
            'spent_at'    => ['required', 'date'],
            'spent_by'    => ['required', 'exists:members,id'],
        ];
    }
}
