<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Poblacion;

class Provincia extends Model
{
    protected $table = 'provincias';
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
     * Atributos asignables.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación con Poblacion
     * Una provincia tiene muchas poblaciones
     */
    public function poblaciones(){
        return $this->hasMany(Poblacion::class);
    }
}
