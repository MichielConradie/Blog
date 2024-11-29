<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Notifications\PostNotification;

class LikeController extends Controller
{
    public function likePost(Request $request, Post $post)
    {
        $post->likes()->firstOrCreate(['user_id' => $request->user()->id]);

        // Notify the post owner
        $post->user->notify(new PostNotification('Your post has been liked.'));

        return response()->json(['message' => 'Post liked']);
    }

    public function likeComment(Request $request, Comment $comment)
    {
        $comment->likes()->firstOrCreate(['user_id' => $request->user()->id]);

        // Notify the comment owner
        $comment->user->notify(new PostNotification('Your comment has been liked.'));

        return response()->json(['message' => 'Comment liked']);
    }
}

