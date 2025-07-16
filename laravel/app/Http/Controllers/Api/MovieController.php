<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Actor;
use Illuminate\Http\Request;
use App\Http\Resources\MovieResource;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    /**
     * Return all movies with related data.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return MovieResource::collection(Movie::with(['genre', 'actors', 'pictures'])->get());
    }

    /**
     * Create a new movie record from the provided data.
     *
     * @param  Request  $request Incoming HTTP request
     * @return MovieResource Newly created movie resource
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'actors' => 'nullable|array',
            'actors.*' => 'string|max:255',
            'pictures' => 'nullable|array',
            'pictures.*' => 'string|max:255',
            'picture_files' => 'nullable|array',
            'picture_files.*' => 'image',
        ]);

        $movie = Movie::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'genre_id' => $validated['genre_id'],
        ]);

        if (isset($validated['actors'])) {
            $actorIds = [];
            foreach ($validated['actors'] as $name) {
                $actor = Actor::firstOrCreate(['name' => $name]);
                $actorIds[] = $actor->id;
            }
            $movie->actors()->sync($actorIds);
        }

        if (isset($validated['pictures'])) {
            foreach ($validated['pictures'] as $url) {
                $movie->pictures()->create(['url' => $url]);
            }
        }

        if ($request->hasFile('picture_files')) {
            foreach ($request->file('picture_files') as $file) {
                $path = $file->store('pictures', 'public');
                $movie->pictures()->create(['url' => Storage::url($path)]);
            }
        }

        $movie->load(['genre', 'actors', 'pictures']);

        return new MovieResource($movie);
    }

    /**
     * Display the specified movie with relations.
     *
     * @param  Movie  $movie Movie instance from route model binding
     * @return MovieResource Movie resource representation
     */
    public function show(Movie $movie)
    {
        $movie->load(['genre', 'actors', 'pictures']);

        return new MovieResource($movie);
    }

    /**
     * Update an existing movie.
     *
     * @param  Request  $request Incoming HTTP request
     * @param  Movie  $movie Movie instance from route model binding
     * @return MovieResource Updated movie resource
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'sometimes|required|exists:genres,id',
            'actors' => 'nullable|array',
            'actors.*' => 'string|max:255',
            'pictures' => 'nullable|array',
            'pictures.*' => 'string|max:255',
            'picture_files' => 'nullable|array',
            'picture_files.*' => 'image',
        ]);

        $movie->fill(array_intersect_key($validated, array_flip(['title', 'description', 'genre_id'])));
        $movie->save();

        if (isset($validated['actors'])) {
            $actorIds = [];
            foreach ($validated['actors'] as $name) {
                $actor = Actor::firstOrCreate(['name' => $name]);
                $actorIds[] = $actor->id;
            }
            $movie->actors()->sync($actorIds);
        }

        if (isset($validated['pictures']) || $request->hasFile('picture_files')) {
            $movie->pictures()->delete();

            if (isset($validated['pictures'])) {
                foreach ($validated['pictures'] as $url) {
                    $movie->pictures()->create(['url' => $url]);
                }
            }

            if ($request->hasFile('picture_files')) {
                foreach ($request->file('picture_files') as $file) {
                    $path = $file->store('pictures', 'public');
                    $movie->pictures()->create(['url' => Storage::url($path)]);
                }
            }
        }

        $movie->load(['genre', 'actors', 'pictures']);

        return new MovieResource($movie);
    }

    /**
     * Delete the specified movie record.
     *
     * @param  Movie  $movie Movie instance from route model binding
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->noContent();
    }
}
