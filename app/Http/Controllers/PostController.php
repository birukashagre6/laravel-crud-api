<?php

namespace App\Http\Controllers;

use App\Models\post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{

    public static function middleware(){
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        return $request->user()->posts()->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, post $post)
    {
        Gate::authorize('modify', $post);
        $fields =  $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $post->update($fields);
         
        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
