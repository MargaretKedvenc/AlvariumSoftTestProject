<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportEmployee extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return ['file' => 'required|mimes:xml|max:' . 1024 * 20]; // 20mB
    }
}
