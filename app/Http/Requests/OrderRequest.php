<?php

namespace App\Http\Requests;

use App\Rules\Phone;
use App\Rules\ProductsForOrder;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'created_at' => 'required|string|date_format:d.m.Y',
            'telephone' => ['nullable', 'string', new Phone()],
            'email' => 'required|email:rfc,dns',
            'address' => 'nullable|string',
            'products' => ['required', 'json', new ProductsForOrder()],
            'total' => 'required|numeric|min:3000',
        ];
    }

    public function attributes() : array
    {
        return [
            'telephone' => 'Телефон',
            'email' => 'Почта',
            'address' => 'Адрес',
            'total' => 'Сумма заказа',
            'created_at' => 'Дата заказа',
        ];
    }
}
