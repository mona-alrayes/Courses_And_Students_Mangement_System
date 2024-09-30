<?php

namespace App\Http\Requests\courses;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class courseServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Common validation rules.
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['string', 'min:3', 'max:255'],
            'description' => ['string', 'min:3', 'max:3000'],
            'start_date' => ['date_format:d-m-Y'],
            'instructor_id' => ['array'], // Expect instructor_id as an array
            'instructor_id.*' => ['integer', 'exists:instructors,id'],
        ];

        if ($this->isMethod('post')) {
            // Required for store request
            $rules['title'][] = 'required';
            $rules['description'][] = 'required';
            $rules['start_date'][] = 'required';
            $rules['instructor_id'][] = 'nullable';
        } else if ($this->isMethod('put')) {
            // Allow optional fields for update request
            $rules['title'][] = 'sometimes';
            $rules['description'][] = 'sometimes';
            $rules['start_date'][] = 'nullable';
            $rules['instructor_id'][] = 'nullable';

        }

        return $rules;
    }

    /**
     * Custom error messages.
     */
    public function messages(): array
    {
        return [
            'required' => 'حقل :attribute مطلوب',
            'string' => 'حقل :attribute يجب أن يكون نصًا وليس أي نوع آخر',
            'max' => 'عدد محارف :attribute لا يجب أن يتجاوز :max محرفًا',
            'min' => 'حقل :attribute يجب أن يكون :min محارف على الأقل',
            'date_format' => 'حقل :attribute يجب أن يكون بصيغة تاريخ صحيحة مثل :format',
            'integer' => 'حقل :attribute يجب ان يكون :integer',
            'exists:instructors,id' => 'حقل :attribute موجود في قاعدة البيانات',
        ];
    }

    /**
     * Custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'start_date' => 'تاريخ بداية الدورة',
            'instructor_id' => 'معرف المدرس',
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if($this->input('title') || $this->input('description') !== null)
        {
            $this->merge([
                'title' => ucwords(strtolower($this->input('title'))),
                'description' => ucwords(strtolower($this->input('description'))),
            ]);
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'خطأ',
            'message' => 'فشلت عملية التحقق من صحة البيانات.',
            'errors' => $validator->errors(),
        ], 422));
    }
}
