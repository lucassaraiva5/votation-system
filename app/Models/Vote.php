<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['company_id', 'slate_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function slate()
    {
        return $this->belongsTo(Slate::class);
    }
}
