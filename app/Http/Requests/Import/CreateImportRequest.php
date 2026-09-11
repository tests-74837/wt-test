<?php

declare(strict_types=1);

namespace App\Http\Requests\Import;

use App\Models\Import;
use App\Models\Supplier;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier' => [
                'required',
                'string',
                Rule::exists((new Supplier())->getTable(), 'name')
            ],
            'external_import_id' => [
                'required',
                'string',
                Rule::unique((new Import())->getTable(), 'external_import_id')
            ],
            'sent_at' => [
                'required',
                'date_format:Y-m-d\TH:i:s\Z',
            ],
            'offers' => [
                'required',
                'array',
            ],
            'offers.*' => [
                'required',
                'array:external_id,check_in,check_out,max_guests,price,currency,available_units,expires_at,property',
            ],
            'offers.*.external_id' => [
                'required',
                'string',
            ],
            'offers.*.check_in' => [
                'required',
                'date_format:Y-m-d',
            ],
            'offers.*.check_out' => [
                'required',
                'date_format:Y-m-d',
            ],
            'offers.*.max_guests' => [
                'required',
                'integer',
                'gt:0'
            ],
            'offers.*.price' => [
                'required',
                'integer',
                'gte:0'
            ],
            'offers.*.currency' => [
                'required',
                'string',
            ],
            'offers.*.available_units' => [
                'required',
                'integer',
                'gte:0',
            ],
            'offers.*.expires_at' => [
                'required',
                'date_format:Y-m-d\TH:i:s\Z',
            ],
            'offers.*.property' => [
                'required',
                'array:code,name,city'
            ],
            'offers.*.property.code' => [
                'required',
                'string',
            ],
            'offers.*.property.name' => [
                'required',
                'string',
            ],
            'offers.*.property.city' => [
                'required',
                'string',
            ],
        ];
    }
}
