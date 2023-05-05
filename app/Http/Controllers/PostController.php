<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index(){
        $posts = Post::latest()->paginate(5);

        return view('posts.index', compact('posts'));
    }

    public function create(){
        return view('posts.create');
    }

    /**
     * store
     * 
     * @param Request $request
     * @return void
     */
    public function store(Request $request){
        // Validate form
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        // Upload image
        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        // Create post
        Post::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'content' => $request->content
        ]);

        // Redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data berhasil disimpan!']);
    }

    public function edit(Post $post){
        return view('posts.edit', compact('post'));
    }

    /**
     * update
     * 
     * @param mixed $request
     * @param mixed $post
     * @return void
     */
    public function update(Request $request, Post $post){
        // Validate form
        $this->validate($request, [
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);

        // Check if image is uploaded
        if($request->hasFile('image')){
            // Upload new image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            // Delete old image
            Storage::delete('public/posts/'.$post->image);

            // Update post with new image
            $post->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'content' => $request->content
            ]);
        } else{
            // Update post without image
            $post->update([
                'title' => $request->title,
                'content' => $request->content
            ]);
        }

        // Redirect to index
        return redirect()->route('posts.index')->with(['success' => "Data berhasil diupdate!"]);
    }

    public function destroy(Post $post){
        // Delete image
        Storage::delete('/public/posts/'.$post->image);

        // Delete post
        $post->delete();

        // Redirect to index
        return redirect()->route('posts.index')->with(['success' => "Data berhasil dihapus!"]);
    }
}
