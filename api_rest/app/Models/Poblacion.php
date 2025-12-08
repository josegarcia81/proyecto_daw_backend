<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poblacion extends Model
{
    protected $table = 'ciudad';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
    /**
     * The attributes that are assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre',
    ];
}
