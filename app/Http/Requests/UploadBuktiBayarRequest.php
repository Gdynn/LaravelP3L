<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadBuktiBayarRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change this based on your authorization logic
    }

    public function rules()
    {
        return [
            'BUKTI_BAYAR' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
            'TOTAL' => 'required|numeric'
        ];
    }
}

