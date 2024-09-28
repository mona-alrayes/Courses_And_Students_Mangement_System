<?php

namespace App\Http\Requests\courses;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends courseServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
