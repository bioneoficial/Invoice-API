<?php

namespace App\Models;

use App\Mail\InvoiceCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'numero',
        'valor',
        'data_emissao',
        'cnpj_remetente',
        'nome_remetente',
        'cnpj_transportador',
        'nome_transportador',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
