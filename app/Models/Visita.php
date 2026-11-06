<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visita extends Model
{
    protected $table = 'visitas';

    protected $fillable = ['bus_id', 'linea_id', 'estacion_id', 'llegada', 'pasajeros_abordo', 'espera_extra_min'];

    protected $casts = ['llegada' => 'datetime'];

    /** @return BelongsTo<Bus, $this> */
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }

    /** @return BelongsTo<Linea, $this> */
    public function linea(): BelongsTo
    {
        return $this->belongsTo(Linea::class);
    }

    /** @return BelongsTo<Estacion, $this> */
    public function estacion(): BelongsTo
    {
        return $this->belongsTo(Estacion::class);
    }
}
