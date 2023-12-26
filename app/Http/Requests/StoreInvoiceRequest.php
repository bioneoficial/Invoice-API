<?php

namespace App\Http\Requests;

use App\Rules\ValidCNPJ;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'numero' => 'required|size:9',
            'valor' => 'required|numeric|min:0.01',
            'data_emissao' => 'required|date|before_or_equal:today',
            'cnpj_remetente' => ['required', new ValidCNPJ()],
            'nome_remetente' => 'required|string|max:100',
            'cnpj_transportador' => ['required', new ValidCNPJ()],
            'nome_transportador' => 'required|string|max:100',
        ];
    }
}
