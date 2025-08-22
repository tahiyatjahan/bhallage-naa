<?php

namespace App\Http\Controllers;

use App\Models\CreativePost;
use App\Models\CreativePostComment;
use App\Models\CreativePostLike;
use App\Models\CommentReply;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
            'media_file' => 'nullable|file', // No size limit, any file type
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

        // BULLETPROOF FILE UPLOAD SYSTEM
        if ($request->hasFile('media_file')) {
            try {
                $file = $request->file('media_file');
                
                // Log everything for debugging
                \Log::info('=== FILE UPLOAD ATTEMPT ===', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                    'temp_path' => $file->getPathname(),
                    'real_path' => $file->getRealPath()
                ]);
                
                // Check if file is valid
                if (!$file->isValid()) {
                    throw new \Exception('Invalid file upload: ' . $file->getErrorMessage() . ' (Error code: ' . $file->getError() . ')');
                }
                
                // Generate unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // METHOD 1: Direct file system copy (most reliable)
                $uploadDir = storage_path('app/public/creative-posts');
                $fullPath = $uploadDir . '/' . $filename;
                
                // Ensure directory exists
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        throw new \Exception('Failed to create upload directory: ' . $uploadDir);
                    }
                }
                
                // Check if directory is writable
                if (!is_writable($uploadDir)) {
                    chmod($uploadDir, 0755);
                    if (!is_writable($uploadDir)) {
                        throw new \Exception('Upload directory is not writable: ' . $uploadDir);
                    }
                }
                
                // Copy file directly
                if (!copy($file->getPathname(), $fullPath)) {
                    throw new \Exception('Failed to copy file to upload directory');
                }
                
                // Verify file was copied
                if (!file_exists($fullPath)) {
                    throw new \Exception('File copy verification failed');
                }
                
                // Set proper permissions
                chmod($fullPath, 0644);
                
                $data['media_file'] = 'creative-posts/' . $filename;
                
                \Log::info('=== FILE UPLOAD SUCCESS ===', [
                    'method' => 'direct_copy',
                    'original_path' => $file->getPathname(),
                    'final_path' => $fullPath,
                    'relative_path' => $data['media_file'],
                    'file_size' => filesize($fullPath),
                    'file_exists' => file_exists($fullPath),
                    'file_readable' => is_readable($fullPath)
                ]);
                
            } catch (\Exception $e) {
                \Log::error('=== FILE UPLOAD FAILED ===', [
                    'error' => $e->getMessage(),
                    'file_info' => [
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ],
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withErrors(['media_file' => 'File upload failed: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle tags
        if (!empty($validated['tags'])) {
            $cleanedTags = array_map(function($tag) {
                return strtolower(ltrim($tag, '#'));
            }, $validated['tags']);
            $data['tags'] = $cleanedTags;
        }

        try {
            $post = CreativePost::create($data);
            \Log::info('Creative post created successfully', ['post_id' => $post->id]);
            
            return redirect()->route('creative-posts.show', $post->id)
                            ->with('status', 'Creative post shared successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to create creative post', ['error' => $e->getMessage()]);
            
            // Clean up uploaded file if post creation failed
            if (isset($data['media_file'])) {
                try {
                    $fullPath = storage_path('app/public/' . $data['media_file']);
                    if (file_exists($fullPath)) {
                        unlink($fullPath);
                        \Log::info('Cleaned up uploaded file after post creation failure');
                    }
                } catch (\Exception $cleanupError) {
                    \Log::error('Failed to cleanup uploaded file', ['error' => $cleanupError->getMessage()]);
                }
            }
            
            return back()->withErrors(['general' => 'Failed to create post: ' . $e->getMessage()])->withInput();
        }
    }

    public function show($id)
    {
        $post = CreativePost::with(['user', 'comments.user', 'comments.replies.user', 'likes'])->findOrFail($id);
        
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
            'media_file' => 'nullable|file', // No size limit, any file type
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

        // Handle file upload with bulletproof system
        if ($request->hasFile('media_file')) {
            try {
                $file = $request->file('media_file');
                
                // Log everything for debugging
                \Log::info('=== FILE UPLOAD ATTEMPT IN UPDATE ===', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'extension' => $file->getClientOriginalExtension(),
                    'is_valid' => $file->isValid(),
                    'error' => $file->getError(),
                    'error_message' => $file->getErrorMessage(),
                    'temp_path' => $file->getPathname(),
                    'real_path' => $file->getRealPath()
                ]);
                
                // Check if file is valid
                if (!$file->isValid()) {
                    throw new \Exception('Invalid file upload: ' . $file->getErrorMessage() . ' (Error code: ' . $file->getError() . ')');
                }
                
                // Delete old file if exists
                if ($post->media_file) {
                    try {
                        $oldFilePath = storage_path('app/public/' . $post->media_file);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                            \Log::info('Old file deleted successfully', ['old_path' => $oldFilePath]);
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old file', ['error' => $e->getMessage()]);
                    }
                }
                
                // Generate unique filename
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                
                // Direct file system copy (most reliable)
                $uploadDir = storage_path('app/public/creative-posts');
                $fullPath = $uploadDir . '/' . $filename;
                
                // Ensure directory exists
                if (!is_dir($uploadDir)) {
                    if (!mkdir($uploadDir, 0755, true)) {
                        throw new \Exception('Failed to create upload directory: ' . $uploadDir);
                    }
                }
                
                // Check if directory is writable
                if (!is_writable($uploadDir)) {
                    chmod($uploadDir, 0755);
                    if (!is_writable($uploadDir)) {
                        throw new \Exception('Upload directory is not writable: ' . $uploadDir);
                    }
                }
                
                // Copy file directly
                if (!copy($file->getPathname(), $fullPath)) {
                    throw new \Exception('Failed to copy file to upload directory');
                }
                
                // Verify file was copied
                if (!file_exists($fullPath)) {
                    throw new \Exception('File copy verification failed');
                }
                
                // Set proper permissions
                chmod($fullPath, 0644);
                
                $data['media_file'] = 'creative-posts/' . $filename;
                
                \Log::info('=== FILE UPLOAD SUCCESS IN UPDATE ===', [
                    'method' => 'direct_copy',
                    'original_path' => $file->getPathname(),
                    'final_path' => $fullPath,
                    'relative_path' => $data['media_file'],
                    'file_size' => filesize($fullPath),
                    'file_exists' => file_exists($fullPath),
                    'file_readable' => is_readable($fullPath)
                ]);
                
            } catch (\Exception $e) {
                \Log::error('=== FILE UPLOAD FAILED IN UPDATE ===', [
                    'error' => $e->getMessage(),
                    'file_info' => [
                        'name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'type' => $file->getMimeType()
                    ],
                    'trace' => $e->getTraceAsString()
                ]);
                
                return back()->withErrors(['media_file' => 'File upload failed: ' . $e->getMessage()])->withInput();
            }
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
            $liked = false;
        } else {
            CreativePostLike::create([
                'user_id' => Auth::id(),
                'creative_post_id' => $id
            ]);
            $liked = true;
            
            // Create notification for post owner
            NotificationService::like(
                postOwnerId: $post->user_id,
                likerId: Auth::id(),
                postType: 'CreativePost',
                postId: $post->id,
                postTitle: $post->title,
                actionUrl: route('creative-posts.show', $post->id)
            );
        }
        
        // Return JSON for AJAX
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes' => $post->likes()->count()
        ]);
    }

    public function comment(Request $request, $id)
    {
        $post = CreativePost::findOrFail($id);
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        CreativePostComment::create([
            'user_id' => Auth::id(),
            'creative_post_id' => $id,
            'content' => $validated['content']
        ]);

        // Create notification for post owner
        NotificationService::comment(
            postOwnerId: $post->user_id,
            commenterId: Auth::id(),
            postType: 'CreativePost',
            postId: $post->id,
            postTitle: $post->title,
            actionUrl: route('creative-posts.show', $post->id)
        );

        // Return JSON for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!'
        ]);
    }

    public function deleteComment($id)
    {
        $comment = CreativePostComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id() && !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }
        
        $comment->delete();
        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.'
        ]);
    }

    public function editComment($id)
    {
        $comment = CreativePostComment::with('creativePost')->findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('creative-posts.edit-comment', compact('comment'));
    }

    public function updateComment(Request $request, $id)
    {
        $comment = CreativePostComment::findOrFail($id);
        
        if ($comment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);
        
        $comment->update([
            'content' => $validated['content']
        ]);
        
        return redirect()->route('creative-posts.show', $comment->creative_post_id)
                        ->with('status', 'Comment updated successfully!');
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



    public function adminDestroy($id)
    {
        $post = CreativePost::findOrFail($id);
        
        // Delete media file if exists
        if ($post->media_file) {
            Storage::disk('public')->delete($post->media_file);
        }
        
        $post->delete();

        return redirect()->route('creative-posts.index')
                        ->with('status', 'Creative post deleted successfully.');
    }

    /**
     * Reply to a comment
     */
    public function replyToComment(Request $request, $id)
    {
        $comment = CreativePostComment::findOrFail($id);
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);

        $reply = CommentReply::create([
            'user_id' => Auth::id(),
            'comment_id' => $id,
            'comment_type' => 'creative_post',
            'content' => $validated['content']
        ]);

        // Create notification for comment owner
        NotificationService::reply(
            commentOwnerId: $comment->user_id,
            replierId: Auth::id(),
            postType: 'CreativePost',
            postId: $comment->creative_post_id,
            postTitle: $comment->creativePost->title,
            actionUrl: route('creative-posts.show', $comment->creative_post_id)
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply added successfully!',
                'reply' => [
                    'id' => $reply->id,
                    'content' => $reply->content,
                    'user' => [
                        'name' => $reply->user->name,
                        'profile_picture' => $reply->user->profile_picture
                    ]
                ]
            ]);
        }

        return back()->with('status', 'Reply added successfully!');
    }

    /**
     * Delete a reply
     */
    public function deleteReply($id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id() && !Auth::user()->is_admin) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.'
                ], 403);
            }
            return back()->with('error', 'Unauthorized action.');
        }
        
        $reply->delete();
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Reply deleted successfully.'
            ]);
        }
        
        return back()->with('status', 'Reply deleted successfully.');
    }

    /**
     * Edit a reply
     */
    public function editReply($id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('creative-posts.edit-reply', compact('reply'));
    }

    /**
     * Update a reply
     */
    public function updateReply(Request $request, $id)
    {
        $reply = CommentReply::findOrFail($id);
        
        if ($reply->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:500'
        ]);
        
        $reply->update(['content' => $validated['content']]);
        
        return back()->with('status', 'Reply updated successfully!');
    }
} 