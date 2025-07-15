<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Picture;

class Movie extends Model
{
    protected $fillable = [
        'title',
        'description',
        'genre_id',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movie_actor');
    }

    public function pictures()
    {
        return $this->hasMany(Picture::class);
    }
}
