<?php

declare(strict_types=1);

namespace App\Http\Requests\Offers;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_reference' => ['required', 'string', 'max:255'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'string', 'max:255'],
        ];
    }
}
