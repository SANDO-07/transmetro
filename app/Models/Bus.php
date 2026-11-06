<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bus extends Model
{
    protected $table = 'buses';

    protected $fillable = ['placa', 'modelo', 'capacidad_maxima', 'linea_id', 'parqueo_id'];

    /** @return BelongsTo<Linea, $this> */
    public function linea(): BelongsTo
    {
        return $this->belongsTo(Linea::class);
    }

    /** @return BelongsTo<Parqueo, $this> */
    public function parqueo(): BelongsTo
    {
        return $this->belongsTo(Parqueo::class);
    }

    /** @return HasOne<Piloto, $this> */
    public function piloto(): HasOne
    {
        return $this->hasOne(Piloto::class);
    }

    /** @return HasMany<Visita, $this> */
    public function visitas(): HasMany
    {
        return $this->hasMany(Visita::class);
    }
}
