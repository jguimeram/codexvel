<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Display a paginated list of movies.
     *
     * @return View Rendered movies index view
     */
    public function index(): View
    {
        $movies = Movie::with('genre')->latest()->paginate(10);
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new movie.
     *
     * @return View Rendered create movie view
     */
    public function create(): View
    {
        $genres = Genre::pluck('name', 'id');
        return view('movies.create', compact('genres'));
    }

    /**
     * Persist a new movie to storage.
     *
     * @param  Request  $request Incoming HTTP request
     * @return RedirectResponse Redirect to movies index
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'actors' => 'nullable|string',
            'pictures' => 'nullable|string',
            'picture_files' => 'nullable|array',
            'picture_files.*' => 'image',
        ]);

        $movie = Movie::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'genre_id' => $validated['genre_id'],
        ]);

        $this->syncActors($movie, $validated['actors'] ?? '');
        $this->syncPictures($movie, $validated['pictures'] ?? '', $request->file('picture_files', []));

        return redirect()->route('movies.index')->with('success', 'Movie created.');
    }

    /**
     * Show the form for editing the specified movie.
     *
     * @param  Movie  $movie Movie instance from route model binding
     * @return View Rendered edit movie view
     */
    public function edit(Movie $movie): View
    {
        $movie->load(['actors', 'pictures']);
        $genres = Genre::pluck('name', 'id');
        return view('movies.edit', compact('movie', 'genres'));
    }

    /**
     * Update the specified movie in storage.
     *
     * @param  Request  $request Incoming HTTP request
     * @param  Movie  $movie Movie instance from route model binding
     * @return RedirectResponse Redirect to movies index
     */
    public function update(Request $request, Movie $movie): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'genre_id' => 'required|exists:genres,id',
            'actors' => 'nullable|string',
            'pictures' => 'nullable|string',
            'picture_files' => 'nullable|array',
            'picture_files.*' => 'image',
        ]);

        $movie->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'genre_id' => $validated['genre_id'],
        ]);

        $this->syncActors($movie, $validated['actors'] ?? '');
        $this->syncPictures($movie, $validated['pictures'] ?? '', $request->file('picture_files', []));

        return redirect()->route('movies.index')->with('success', 'Movie updated.');
    }

    /**
     * Remove the specified movie from storage.
     *
     * @param  Movie  $movie Movie instance from route model binding
     * @return RedirectResponse Redirect back to movies list
     */
    public function destroy(Movie $movie): RedirectResponse
    {
        $movie->delete();
        return redirect()->route('movies.index')->with('success', 'Movie deleted.');
    }

    /**
     * Synchronize actors associated with a movie.
     *
     * @param  Movie   $movie  Movie being updated
     * @param  string  $actors Comma separated list of actor names
     * @return void
     */
    private function syncActors(Movie $movie, string $actors): void
    {
        $names = array_filter(array_map('trim', explode(',', $actors)));
        $ids = [];
        foreach ($names as $name) {
            $ids[] = Actor::firstOrCreate(['name' => $name])->id;
        }
        $movie->actors()->sync($ids);
    }

    /**
     * Synchronize uploaded or external pictures for a movie.
     *
     * @param  Movie   $movie    Movie being updated
     * @param  string  $pictures Comma separated list of picture URLs
     * @param  array<int, \Illuminate\Http\UploadedFile>  $files  Uploaded picture files
     * @return void
     */
    private function syncPictures(Movie $movie, string $pictures, array $files = []): void
    {
        $urls = array_filter(array_map('trim', explode(',', $pictures)));

        foreach ($files as $file) {
            $path = $file->store('pictures', 'public');
            $urls[] = \Storage::url($path);
        }

        $movie->pictures()->delete();
        foreach ($urls as $url) {
            $movie->pictures()->create(['url' => $url]);
        }
    }
}
