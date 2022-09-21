<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Movie\Store;
use App\Http\Requests\Admin\Movie\Update;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::withTrashed()->get();

        return Inertia::render(
            'Admin/Movie/Index',
            compact('movies')
        );
    }

    public function create()
    {
        return Inertia::render(
            'Admin/Movie/Create'
        );
    }

    public function store(Store $request)
    {
        $data = $request->validated();
        $data['thumbnail'] = Storage::disk('public')->put('movies', $request->file('thumbnail'));
        $data['slug'] = Str::slug($data['name']);

        $movie = Movie::create($data);

        return redirect(route('admin.dashboard.movie.index'))
            ->with([
                'message' => 'Movie inserted successfully.',
                'type' => 'success'
            ]);
    }

    public function edit(Movie $movie)
    {
        return Inertia::render(
            'Admin/Movie/Edit',
            compact('movie')
        );
    }

    public function update(Update $request, Movie $movie)
    {
        $data = $request->validated();

        if ($request->file('thumbnail')) {
            $data['thumbnail'] = Storage::disk('public')->put('movies', $request->file('thumbnail'));
            Storage::disk('local')->delete($movie->thumbnail);
        } else {
            $data['thumbnail'] = $movie->thumbnail;
        }

        $movie->update($data);

        return redirect(route('admin.dashboard.movie.index'))
            ->with([
                'message' => 'Movie updated successfully.',
                'type' => 'success'
            ]);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return redirect(route('admin.dashboard.movie.index'))
            ->with([
                'message' => 'Movie deleted successfully.',
                'type' => 'success'
            ]);
    }

    public function restore($movie)
    {
        Movie::withTrashed()->find($movie)->restore();

        return redirect(route('admin.dashboard.movie.index'))
            ->with([
                'message' => 'Movie restored successfully.',
                'type' => 'success'
            ]);
    }
}
