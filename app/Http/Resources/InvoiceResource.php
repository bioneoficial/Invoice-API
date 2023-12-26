<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_id' => $this->id,
            'numero' => $this->numero,
            'valor' => $this->valor,
            'data_emissao' => $this->data_emissao,
            'cnpj_remetente' => $this->cnpj_remetente,
            'nome_remetente' => $this->nome_remetente,
            'cnpj_transportador' => $this->cnpj_transportador,
            'nome_transportador' => $this->nome_transportador,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
