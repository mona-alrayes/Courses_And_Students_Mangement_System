<?php

namespace App\Http\Requests\students;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StudentServiceRequest extends FormRequest
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
            'name' => ['string', 'min:3', 'max:255'],
            'email' => ['string', 'email', 'unique:students,email,' . $this->route('student')],
            'course_id' => ['integer', 'exists:courses,id'],
        ];

        if ($this->isMethod('post')) {
            // Required for store request
            $rules['name'][] = 'required';
            $rules['email'][] = 'required';
            $rules['course_id'][] = 'nullable';
        } else if ($this->isMethod('put')) {
            // Allow optional fields for update request
            $rules['name'][] = 'sometimes';
            $rules['email'][] = 'sometimes';
            $rules['course_id'][] = 'nullable';
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
            'exists' => 'حقل :attribute غير موجود في بياناتنا '
        ];
    }

    /**
     * Custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'name' => 'الاسم',
            'email' => 'الاختصاص',
            'course_id' => 'معرف الدورة'
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('name')!== null) {
            $this->merge([
                'name' => ucwords(strtolower($this->input('name'))),
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
