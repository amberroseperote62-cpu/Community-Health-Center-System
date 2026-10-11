<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:150',
            'generic_name'   => 'required|string|max:150',
            'category'       => 'required|string|max:100',
            'dosage_form'    => 'required|in:tablet,syrup,capsule,injection,ointment,drops,other',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level'  => 'required|integer|min:0',
            'expiry_date'    => 'required|date',
        ];
    }
}
