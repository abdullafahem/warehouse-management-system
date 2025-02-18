<?php

namespace App\Http\Requests\Warehouse\Trucks;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTruckRequest extends FormRequest
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
            'chassis_number' => 'required|string|max:255|unique:trucks,chassis_number,' . $this->truck->id,
            'license_plate' => 'required|string|max:255|unique:trucks,license_plate,' . $this->truck->id,
            'container_volume' => 'required|numeric|min:0'
        ];
    }
}
