<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Time;

class Estadio extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'capacidade', 'foto', 'data_fundacao', 'apelido', 'time_id'];

    public function times()
    {
        return $this->belongsToMany(Time::class);
    }
}
