<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'student_id' => 'required',
            'term' => 'required',
            'maths' => 'required|integer|between:1,200',
            'science' => 'required|integer|between:1,200',
            'history' => 'required|integer|between:1,200',
        ];
    }
}
