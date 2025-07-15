<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    protected $fillable = ['url'];

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }
}
