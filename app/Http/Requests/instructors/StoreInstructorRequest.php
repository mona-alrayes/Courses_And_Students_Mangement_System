<?php

namespace App\Http\Requests\instructors;

class StoreInstructorRequest extends instructorServiceRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
