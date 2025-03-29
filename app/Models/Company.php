<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'hash', 'phone'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
