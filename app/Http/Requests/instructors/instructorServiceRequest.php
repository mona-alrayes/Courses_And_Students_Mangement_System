<?php

namespace App\Http\Requests\instructors;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class instructorServiceRequest extends FormRequest
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
            'specialty' => ['string', 'min:3', 'max:255'],
            'experience' => ['integer', 'between:1,40'],
            'course_id' => ['integer', 'exists:courses,id'],
        ];

        if ($this->isMethod('post')) {
            // Required for store request
            $rules['name'][] = 'required';
            $rules['specialty'][] = 'required';
            $rules['experience'][] = 'required';
            $rules['course_id'][] = 'nullable';  // don't have to add course while making a new teacher
        } else if ($this->isMethod('put')) {
            // Allow optional fields for update request
            $rules['name'][] = 'sometimes';
            $rules['specialty'][] = 'sometimes';
            $rules['experience'][] = 'nullable';
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
            'max' => 'عدد محارف :attribute لا يجب أن يتجاوز :max محرفًا',
            'min' => 'حقل :attribute يجب أن يكون :min محارف على الأقل',
            'integer' => 'حقل :attribute يجب أن يكون رقماً',
            'between' => 'عدد سنوات الخبرة يجب ان تكون :between  ',
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
            'specialty' => 'الاختصاص',
            'experience' => 'سنوات الخبرة',
            'course_id' => 'معرف الدورة'
        ];
    }

    /**
     * Prepare data before validation.
     */
    protected function prepareForValidation(): void
    {
        if($this->input('name') || $this->input('specialty') !== null) {
            $this->merge([
                'name' => ucwords(strtolower($this->input('name'))),
                'specialty' => ucwords(strtolower($this->input('specialty'))),
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
