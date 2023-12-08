<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => ["required", "unique:products,title"],
            "published" => ["integer", "between:0,1"],
            "description" => "min:5",
            "price" => ["required", "numeric"],
            "stock" => "numeric",
            "category_id" => ["required", "exists:categories,id"],
            "images.*" => ["nullable", "mimes:jpeg,png,jpg"]
        ];
    }
}
