<?php

namespace App\Http\Requests\Warehouse\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class StoreInventoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'package_volume' => 'required|numeric|min:0'
        ];
    }
}
