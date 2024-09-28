<?php

namespace App\Http\Requests\instructors;

class UpdateInstructorRequest extends instructorServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
