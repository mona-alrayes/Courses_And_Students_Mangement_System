<?php

namespace App\Http\Requests;
namespace App\Http\Requests\students;


use Illuminate\Foundation\Http\FormRequest;

class UpdatestudentRequest extends StudentServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
