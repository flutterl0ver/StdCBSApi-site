<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
        $rules = [
            'passengers_count' => 'required|integer|min:1',
            'token' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_email' => 'required|string'
        ];
        $this->validate($rules);

        $passengers_count = $this->post('passengers_count');
        for($i = 0; $i < $passengers_count; $i++)
        {
            $rules = array_merge($rules, [
                'name'.$i => 'required|string',
                'surname'.$i => 'required|string',
                'patronymic'.$i => 'nullable|string',
                'passenger_type'.$i => 'required|string|in:ADULT,CHILD,INFANT',
                'gender'.$i => 'required|string|in:male,female',
                'birth_date'.$i => 'required|date',
                'citizenship'.$i => 'required|string',
                'document_type'.$i => 'required|string',
                'document_number'.$i => 'required|string',
                'document_expire_date'.$i => 'required|date',
                'passenger_phone'.$i => 'required|string',
                'no_email'.$i => 'nullable|string',
                'passenger_email'.$i => 'required_without:no_email'.$i.'|nullable|string'
            ]);
        }

        return $rules;
    }
}
