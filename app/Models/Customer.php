<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'cpf', 'cellphone', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
