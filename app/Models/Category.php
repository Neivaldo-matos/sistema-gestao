<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    //
    protected $fillable = ['nome'];

    //uma categoria tem muitas tarefas
    public function tasks(): HasMany
    {
        return $this->hasMany(Tasks::class);
    }
}
