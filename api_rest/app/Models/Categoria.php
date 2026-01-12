<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
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
        'descripcion',
    ];
    
    /**
     * Los atributos que deben ser convertidos (casts).
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];
}
