<?php

namespace App\Http\Requests\Warehouse\Orders;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleDeliveryRequest extends FormRequest
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
            'delivery_date' => 'required|date|after_or_equal:today',
            'truck_ids' => 'required|array',
            'truck_ids.*' => 'exists:trucks,id'
        ];
    }
}
