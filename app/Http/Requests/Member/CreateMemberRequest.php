<?php

namespace App\Http\Requests\Member;

use App\Rules\CombineUnique;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['string', 'required', 'max:255'],
            'phone' => ['numeric', 'required', 'digits_between:7,15'],
            'photo' => ['string', 'nullable', 'max:255'],
        ];
    }
}
