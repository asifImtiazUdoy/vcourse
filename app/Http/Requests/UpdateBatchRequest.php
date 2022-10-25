<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBatchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'sometimes',
            'number' => 'sometimes',
            'course_id' => 'sometimes',
            'status' => 'sometimes',
            'max_seat' => 'sometimes',
            'last_ennrollment_date' => 'sometimes',
            'class_starting_date' => 'sometimes',
        ];
    }
}
