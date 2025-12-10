<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Poblacion;

class Provincia extends Model
{
    protected $table = 'provincias';
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

    // Relación con Poblacion
    // Un provincia tiene muchas poblaciones
    // Un poblacion pertenece a una provincia
    // Ejemplo de uso:
    // $provincia = Provincia::find(1);
    // $poblaciones = $provincia->poblaciones;

    public function poblaciones(){
        return $this->hasMany(Poblacion::class);
    }
}
