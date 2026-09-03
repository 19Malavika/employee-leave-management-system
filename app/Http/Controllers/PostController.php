<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'categories', 'comments'])
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('posts.index', compact('posts', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],

            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'preset_image' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1000'],
        ]);

        $imagePath = null;

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('preset_image')) {
            $imagePath = $request->input('preset_image');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(),
            'featured_image' => $imagePath,
        ]);

        $post->categories()->sync($validated['category_ids'] ?? []);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $categories = Category::orderBy('name')->get();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],

            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],

            'featured_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'preset_image' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1000'],
        ]);

        $imagePath = $post->featured_image;

        if ($request->hasFile('featured_image')) {
            if (
                $imagePath &&
                !Str::startsWith($imagePath, ['http://', 'https://'])
            ) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('featured_image')
                ->store('posts', 'public');
        } elseif ($request->filled('preset_image')) {
            $imagePath = $request->input('preset_image');
        } elseif ($request->filled('image_url')) {
            $imagePath = $request->input('image_url');
        }

        $post->update([
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'content' => $validated['content'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(),
            'featured_image' => $imagePath,
        ]);

        $post->categories()->sync($validated['category_ids'] ?? []);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if (
            $post->featured_image &&
            !Str::startsWith($post->featured_image, ['http://', 'https://'])
        ) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}