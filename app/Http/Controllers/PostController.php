<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::query();
        if ($request->has('search')) {
            $posts->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
        }
        if ($request->has('user_id')) {
            $posts->where('user_id', $request->user_id);
        }
        if ($request->has('category')) {
            $posts->whereHas('categories', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }
        return response()->json($posts->paginate(10));
    }
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);
        return response()->json($post, 201);
    }
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->update($request->only(['title', 'content']));
        return response()->json($post);
    }
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id != Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
