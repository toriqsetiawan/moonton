<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Movie\Store;
use App\Models\Movie;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();

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
}
