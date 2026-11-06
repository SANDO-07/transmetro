<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Municipalidad extends Model
{
    protected $table = 'municipalidades';

    protected $fillable = ['nombre', 'departamento'];

    /** @return HasMany<Linea, $this> */
    public function lineas(): HasMany
    {
        return $this->hasMany(Linea::class);
    }

    /** @return HasMany<Estacion, $this> */
    public function estaciones(): HasMany
    {
        return $this->hasMany(Estacion::class);
    }
}
