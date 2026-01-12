<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Tipo de clave primaria y autoincremento.
     */
    protected $primaryKey = 'id'; // Definir nombre de la clave primaria
    public $incrementing = true; // Definir que la clave primaria es autoincrementable
    protected $keyType = 'int'; // Definir que la clave primaria es de tipo entero
    protected $table = 'usuarios'; // Definir nombre de la tabla
    public $timestamps = false; // Desactivar timestamps porque la tabla en AWS no tiene created_at/updated_at
    /**
     * Los atributos que son asignables masivamente.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
        'apellido',
        'email',
        'password',
        'provincia_id',
        'ciudad_id',
        'descripcion',
        'horas_saldo',
        'valoracion',
        'rol_id',
        'ruta_img',
        'direccion'
    ];

    /**
     * Valores por defecto de los atributos.
     *
     * @var array
     */
    protected $attributes = [
        'rol_id' => 3, // Valor por defecto para registro público
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos (cast).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'horas_saldo' => 'integer',
        'valoracion' => 'float', // Ojo cambiado de valoraciones a valoracion
        'provincia_id' => 'integer',
        'ciudad_id' => 'integer',
        'rol_id' => 'integer',
        'ruta_img' => 'string',
        'direccion' => 'string',
    ];

    /**
     * Relación con Provincia
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincia_id');
    }

    /**
     * Relación con Ciudad (Poblacion)
     */
    public function ciudad()
    {
        return $this->belongsTo(Poblacion::class, 'ciudad_id');
    }

    /**
     * Relación con Rol
     */
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }
}
