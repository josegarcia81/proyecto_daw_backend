<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
        /**
     * The primary key type and auto-incrementing.
     */
    protected $primaryKey = 'id'; // Definir nombre de la clave primaria
    public $incrementing = true; // Definir que la clave primaria es autoincrementable
    protected $keyType = 'int'; // Definir que la clave primaria es de tipo entero
    protected $table = 'servicios';
    public $timestamps = false;
    
    protected $fillable = [
        'usuario_id',
        'categoria_id',
        'tipo',
        'titulo',
        'descripcion',
        'provincia_id',
        'ciudad_id',
        'horas_estimadas',
        'estado',
        'ruta_img'
    ];
    
    protected $casts = [
        'usuario_id' => 'integer',
        'categoria_id' => 'integer',
        'provincia_id' => 'integer',
        'ciudad_id' => 'integer',
        'horas_estimadas' => 'integer',
        'ruta_img' => 'string',
    ];
    
    protected $attributes = [
        'estado' => 'activo', // Valor por defecto
    ];

    /**
     * Relación con Usuario
     * El servicio pertenece a un usuario que lo publica
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Relación con Categoría
     * El servicio pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    /**
     * Relación con Provincia
     * El servicio se ubica en una provincia
     */
    public function provinciaRelacion()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    /**
     * Relación con Ciudad (Poblacion)
     * El servicio se ubica en una ciudad
     */
    public function ciudadRelacion()
    {
        return $this->belongsTo(Poblacion::class, 'ciudad_id');
    }
}
