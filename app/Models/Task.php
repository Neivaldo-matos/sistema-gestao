<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importante importar!

class Task extends Model
{
    // Adicione esta propriedade abaixo
    protected $fillable = [
        'titulo',
        'descricao',
        'concluida',
        'category_id', // atributo de outra tabela
    ];

    // definicao que o Task pertence a uma category
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
