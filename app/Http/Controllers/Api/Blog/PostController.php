<?php

// ВАЖЛИВО: Тут немає слова Admin!
namespace App\Http\Controllers\Api\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;

class PostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::paginate(10);
        return response()->json($posts);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $post = BlogPost::findOrFail($id);
        return response()->json(['data' => $post]);
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
