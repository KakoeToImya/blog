<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function home(){
        $posts= Post::latest()->take(3)->get();
        return view('home', compact('posts'));
    }

    public function index(){
        $posts= Post::latest()->paginate(5);
        return view('posts.index',compact('posts'));
    }
    public function show($id){
        $post = Post::with(['category', 'user', 'comments.user'])->findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function create(){
        return view('posts.create');
    }

    public function store(PostRequest $request){
        $validated = $request->validated();
        $validated['user_id']=auth()->id();
        $validated['author_name']=auth()->user()->name;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        Post::create($validated);

        return redirect('/posts')->with('success','пост создан');

    }

    public function edit($id){
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));

    }

    public function update(PostRequest $request,$id){
        $validated = $request->validated();



        $post = Post::findOrFail($id);
        $post->update($validated);

        if ($post->user_id !== auth()->id()) {
            abort(403, 'Недостаточно прав для редактирования этого поста');
        }

        $validated['author_name']=auth()->user()->name;

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect('/posts')->with('success','пост обновлен');
    }

    public function destroy($id){
        $post= Post::findOrFail($id);
        $post->delete();

        return redirect('/posts')->with('success','пост удален');
    }


    public function category(Category $category)
    {
        $posts = $category->posts()->latest()->get();
        return view('posts.category', compact('posts', 'category'));
    }
}
