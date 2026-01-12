<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valoracion extends Model
{
    protected $table = 'valoraciones';
    /**
     * Tipo de clave primaria y autoincremento.
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Gestión de timestamps.
     * Solo tiene created_at, no updated_at.
     */
    const UPDATED_AT = null;
    
    /**
     * Atributos asignables masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaccion_id',
        'valorador_id',
        'valorado_id',
        'puntuacion',
        'comentario',
    ];
    
    /**
     * Los atributos que deben ser convertidos (casts).
     *
     * @var array
     */
    protected $casts = [
        'transaccion_id' => 'integer',
        'valorador_id' => 'integer',
        'valorado_id' => 'integer',
        'puntuacion' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relación con Transacción
     * La valoración pertenece a una transacción
     */
    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'transaccion_id');
    }

    /**
     * Relación con Usuario Valorador
     * El usuario que realiza la valoración
     */
    public function valorador()
    {
        return $this->belongsTo(User::class, 'valorador_id');
    }

    /**
     * Relación con Usuario Valorado
     * El usuario que recibe la valoración
     */
    public function valorado()
    {
        return $this->belongsTo(User::class, 'valorado_id');
    }
}
