<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    protected $table = 'transacciones';
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
        'servicio_id',
        'usuario_solicitante_id',
        'usuario_ofertante_id',
        'horas',
        'estado',
        'fecha_confirmacion',
    ];
    
    /**
     * Los atributos que deben ser convertidos (cats).
     *
     * @var array
     */
    protected $casts = [
        'servicio_id' => 'integer',
        'usuario_solicitante_id' => 'integer',
        'usuario_ofertante_id' => 'integer',
        'horas' => 'integer',
        'fecha_confirmacion' => 'datetime',
        'created_at' => 'datetime'
    ];
    
    /**
     * Valores por defecto de los atributos.
     *
     * @var array
     */
    protected $attributes = [
        'estado' => 'pendiente', // Valor por defecto
    ];

    /**
     * Relación con Servicio
     * La transacción pertenece a un servicio
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    /**
     * Relación con Usuario Solicitante
     * El usuario que solicita/demanda el servicio
     */
    public function usuarioSolicitante()
    {
        return $this->belongsTo(User::class, 'usuario_solicitante_id');
    }

    /**
     * Relación con Usuario Ofertante
     * El usuario que ofrece/provee el servicio
     */
    public function usuarioOfertante()
    {
        return $this->belongsTo(User::class, 'usuario_ofertante_id');
    }
}
