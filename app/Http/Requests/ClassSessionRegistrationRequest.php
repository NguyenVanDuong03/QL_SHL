<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassSessionRegistrationRequest extends FormRequest
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
            'open_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:open_date',
        ];
    }

    public function messages(): array
    {
        return [
            'open_date.required' => 'Ngày bắt đầu không được để trống',
            'open_date.date' => 'Ngày bắt đầu không đúng định dạng',
            'end_date.required' => 'Ngày kết thúc không được để trống',
            'end_date.date' => 'Ngày kết thúc không đúng định dạng',
            'end_date.after_or_equal' => 'Ngày kết thúc phải lớn hơn hoặc bằng ngày bắt đầu',
        ];
    }
}
