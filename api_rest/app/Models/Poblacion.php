<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    protected $table = 'ciudades';
    /**
     * Tipo de clave primaria y autoincremento.
     */
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Gestión de timestamps.
     * No utilizamos timestamps en este modelo.
     */
    public $timestamps = false;
    /**
     * Atributos asignables masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación con Provincia
     * Cada población (ciudad) pertenece a una provincia
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }
}
