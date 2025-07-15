<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        $movies = [
            [
                'title' => 'Avengers: Endgame',
                'description' => 'Super heroes unite to battle Thanos.',
                'genre' => 'Action',
                'actors' => [
                    'Robert Downey Jr.',
                    'Scarlett Johansson',
                    'Chris Evans',
                ],
                'pictures' => [
                    'https://example.com/endgame1.jpg',
                    'https://example.com/endgame2.jpg',
                ],
            ],
            [
                'title' => 'Iron Man',
                'description' => 'Tony Stark becomes the armored hero.',
                'genre' => 'Action',
                'actors' => [
                    'Robert Downey Jr.',
                ],
                'pictures' => [
                    'https://example.com/ironman1.jpg',
                ],
            ],
            [
                'title' => 'Lucy',
                'description' => 'A woman unlocks the full potential of her brain.',
                'genre' => 'Science Fiction',
                'actors' => [
                    'Scarlett Johansson',
                ],
                'pictures' => [
                    'https://example.com/lucy1.jpg',
                ],
            ],
        ];

        foreach ($movies as $data) {
            $genre = Genre::firstOrCreate(['name' => $data['genre']]);

            $movie = Movie::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'genre_id' => $genre->id,
            ]);

            $actorIds = [];
            foreach ($data['actors'] as $name) {
                $actor = Actor::firstOrCreate(['name' => $name]);
                $actorIds[] = $actor->id;
            }
            $movie->actors()->sync($actorIds);

            foreach ($data['pictures'] as $url) {
                $movie->pictures()->create(['url' => $url]);
            }
        }
    }
}
