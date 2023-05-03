<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'productos';

	protected $fillable = [
		'nombre'
	];

	public function categoria(): HasOne
    {
        return $this->hasOne(Categoria::class);
    }

}


