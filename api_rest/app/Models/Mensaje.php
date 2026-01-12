<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';
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
        'emisor_id',
        'receptor_id',
        'servicio_id',
        'mensaje',
        'leido',
    ];
    
    /**
     * Los atributos que deben ser convertidos (casts).
     *
     * @var array
     */
    protected $casts = [
        'emisor_id' => 'integer',
        'receptor_id' => 'integer',
        'servicio_id' => 'integer',
        'leido' => 'boolean',
        'created_at' => 'datetime',
    ];
    
    /**
     * Valores por defecto de los atributos.
     *
     * @var array
     */
    protected $attributes = [
        'leido' => false, // Valor por defecto
    ];

    /**
     * Relación con Usuario Emisor
     */
    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    /**
     * Relación con Usuario Receptor
     */
    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }

    /**
     * Relación con Servicio
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
