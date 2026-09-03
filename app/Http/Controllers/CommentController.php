<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
            'thumbnail' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($validated['parent_id'] ?? null) {
            $parent = Comment::where('id', $validated['parent_id'])
                ->where('post_id', $post->id)
                ->firstOrFail();
        }

        $thumbnailPath = null;

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')
                ->store('comments', 'public');
        }

        Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'parent_id' => $validated['parent_id'] ?? null,
            'comment' => $validated['comment'],
            'thumbnail' => $thumbnailPath,
        ]);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Comment added successfully.');
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();

        if (
            $user->role !== 'admin' &&
            $user->id !== $comment->user_id
        ) {
            abort(403);
        }

        if ($comment->thumbnail) {
            Storage::disk('public')->delete($comment->thumbnail);
        }

        $comment->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Comment deleted successfully.');
    }
}