<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['required', 'integer', 'min:0'],
            'infants' => ['required', 'integer', 'min:0'],
            'has_date_from' => ['nullable'],
            'date_to' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'date_from' => ['nullable', 'required_if:has_date_from, true', 'exclude_unless:has_date_from, true', 'date', 'date_format:Y-m-d', 'after_or_equal:date_to']
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Пожалуйста, заполните это поле.',
            'after_or_equal' => 'Введена слишком ранняя дата.'
        ];
    }
}
