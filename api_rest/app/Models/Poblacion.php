<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    protected $table = 'ciudades';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    /**
     * Atributos asignables.
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
