<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectContractorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
     protected function prepareForValidation()
    {
        if ($this->has('value')) {
            $this->merge([
                'contract_date' => $this->contract_date ?: null,
                'start_date' => $this->start_date ?: null,
                'end_date' => $this->end_date ?: null,
                'value' => preg_replace('/[^\d.]/', '', $this->value),
            ]);
        }
        }
}
