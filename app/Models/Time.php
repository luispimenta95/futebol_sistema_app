<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Estadio;

class Time extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'escudo', 'fundacao', 'apelido'];

    public function estadios()
    {
        return $this->hasOne(Estadio::class);
    }
}
