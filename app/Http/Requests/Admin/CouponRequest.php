<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string',
            'link' => 'required|url',
            'amount' => 'required|integer',
            'type' => 'required|integer',
            'published_at' => 'required|date',
            'expired_at' => 'nullable|date',
        ];
    }
}
