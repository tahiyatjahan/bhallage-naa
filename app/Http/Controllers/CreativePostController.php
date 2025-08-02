<?php

namespace App\Http\Controllers;

use App\Models\CreativePost;
use App\Models\CreativePostComment;
use App\Models\CreativePostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreativePostController extends Controller
{
    public function index(Request $request)
    {
        $query = CreativePost::with(['user', 'comments', 'likes'])->public()->latest();
        
        // Filter by category if provided
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }
        
        // Filter by tags if provided
        if ($request->has('tags') && $request->tags) {
            $query->withTags($request->tags);
        }
        
        $posts = $query->paginate(12);
        $categories = CreativePost::getCategories();
        
        return view('creative-posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = CreativePost::getCategories();
        return view('creative-posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:music,artwork,poetry,photography,writing,video,craft,other',
            'media_file' => 'nullable|file|max:10240', // 10MB max
            'content' => 'nullable|string|max:5000',
            'external_link' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_public' => 'boolean'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'content' => $validated['content'],
            'external_link' => $validated['external_link'],
            'is_public' => $validated['is_public'] ?? true
        ];

        // Handle file upload
        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('creative-posts', $filename, 'public');
            $data['media_file'] = $path;
        }

        // Handle tags
        if (!empty($validated['tags'])) {
            $cleanedTags = array_map(function($tag) {
                return strtolower(ltrim($tag, '#'));
            }, $validated['tags']);
            $data['tags'] = $cleanedTags;
        }

        $post = CreativePost::create($data);

        return redirect()->route('creative-posts.show', $post->id)
                        ->with('status', 'Creative post shared successfully!');
    }

    public function show($id)
    {
        $post = CreativePost::with(['user', 'comments.user', 'likes'])->findOrFail($id);
        
        // Check if user can view private posts
        if (!$post->is_public && $post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'This post is private.');
        }
        
        $categories = CreativePost::getCategories();
        
        return view('creative-posts.show', compact('post', 'categories'));
    }

    public function edit($id)
    {
        $post = CreativePost::findOrFail($id);
        
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = CreativePost::getCategories();
        
        return view('creative-posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $post = CreativePost::findOrFail($id);
        
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'required|in:music,artwork,poetry,photography,writing,video,craft,other',
            'media_file' => 'nullable|file|max:10240',
            'content' => 'nullable|string|max:5000',
            'external_link' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'is_public' => 'boolean'
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'content' => $validated['content'],
            'external_link' => $validated['external_link'],
            'is_public' => $validated['is_public'] ?? true
        ];

        // Handle file upload
        if ($request->hasFile('media_file')) {
            // Delete old file if exists
            if ($post->media_file) {
                Storage::disk('public')->delete($post->media_file);
            }
            
            $file = $request->file('media_file');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('creative-posts', $filename, 'public');
            $data['media_file'] = $path;
        }

        // Handle tags
        if (!empty($validated['tags'])) {
            $cleanedTags = array_map(function($tag) {
                return strtolower(ltrim($tag, '#'));
            }, $validated['tags']);
            $data['tags'] = $cleanedTags;
        }

        $post->update($data);

        return redirect()->route('creative-posts.show', $post->id)
                        ->with('status', 'Creative post updated successfully!');
    }

    public function destroy($id)
    {
        $post = CreativePost::findOrFail($id);
        
        if ($post->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete media file if exists
        if ($post->media_file) {
            Storage::disk('public')->delete($post->media_file);
        }
        
        $post->delete();

        return redirect()->route('creative-posts.index')
                        ->with('status', 'Creative post deleted successfully.');
    }

    public function like($id)
    {
        $post = CreativePost::findOrFail($id);
        
        // Check if user already liked
        $existingLike = CreativePostLike::where('user_id', Auth::id())
                                       ->where('creative_post_id', $id)
                                       ->first();
        
        if ($existingLike) {
            $existingLike->delete();
            $message = 'Like removed.';
        } else {
            CreativePostLike::create([
                'user_id' => Auth::id(),
                'creative_post_id' => $id
            ]);
            $message = 'Post liked!';
        }
        
        return back()->with('status', $message);
    }

    public function comment(Request $request, $id)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        CreativePostComment::create([
            'user_id' => Auth::id(),
            'creative_post_id' => $id,
            'content' => $validated['content']
        ]);

        return back()->with('status', 'Comment added successfully!');
    }

    public function deleteComment($id)
    {
        $comment = CreativePostComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return back()->with('error', 'Unauthorized action.');
        }
        
        $comment->delete();
        return back()->with('status', 'Comment deleted successfully.');
    }

    public function filterByCategory($category)
    {
        $posts = CreativePost::with(['user', 'comments', 'likes'])
                            ->public()
                            ->byCategory($category)
                            ->latest()
                            ->paginate(12);
        
        $categories = CreativePost::getCategories();
        
        return view('creative-posts.index', compact('posts', 'categories', 'category'));
    }

    // Admin methods
    public function adminIndex()
    {
        $posts = CreativePost::with(['user', 'comments', 'likes'])
                            ->latest()
                            ->paginate(20);
        
        $categories = CreativePost::getCategories();
        
        return view('admin.creative-posts.index', compact('posts', 'categories'));
    }

    public function adminToggleFeatured($id)
    {
        $post = CreativePost::findOrFail($id);
        $post->is_featured = !$post->is_featured;
        $post->save();
        
        $status = $post->is_featured ? 'featured' : 'unfeatured';
        return back()->with('status', "Post {$status} successfully.");
    }

    public function adminDestroy($id)
    {
        $post = CreativePost::findOrFail($id);
        
        // Delete media file if exists
        if ($post->media_file) {
            Storage::disk('public')->delete($post->media_file);
        }
        
        $post->delete();

        return back()->with('status', 'Creative post deleted successfully.');
    }
} 