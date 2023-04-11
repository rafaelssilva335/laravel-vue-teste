<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;

    protected $fillable = [
        'endereco',
        'estado',
        'cidade',
    ];

    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }
}
