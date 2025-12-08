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
     * The primary key type and auto-incrementing.
     */
    protected $primaryKey = 'id'; // Definir nombre de la clave primaria
    public $incrementing = true; // Definir que la clave primaria es autoincrementable
    protected $keyType = 'int'; // Definir que la clave primaria es de tipo entero
    protected $table = 'usuarios'; // Definir nombre de la tabla
    public $timestamps = false; // Desactivar timestamps porque la tabla en AWS no tiene created_at/updated_at
    /**
     * The attributes that are mass assignable.
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
    ];

    /**
     * Default attribute values.
     *
     * @var array
     */
    protected $attributes = [
        'rol_id' => 3, // Valor por defecto para registro público
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'horas_saldo' => 'integer',
        'valoraciones' => 'float',
        'provincia_id' => 'integer',
        'ciudad_id' => 'integer',
        'rol_id' => 'integer',
    ];
}
