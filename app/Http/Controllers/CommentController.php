<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\CommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function __construct(){
        $this->middleware('auth')->except(['index']);
    }

    public function store(CommentRequest $request, Post $post){
        $validated = $request->validated();

        $data=[
            'content'=>$validated['content'],
            'user_id'=>auth()->id(),
            'post_id'=>$post->id,
        ];

        if ($request->hasFile('attachment')){
            $data['attachment'] = $validated['attachment']->store('comments', 'public');
        }


        Comment::create($data);
        return back()->with('success','комментарий добавлен');

    }

    public function destroy(Comment $comment){
        if ($comment->user_id!==auth()->id()){
            abort(403, 'недостаточно прав для удаления комментария');
        }

        $comment->delete();

        return back()->with('success','комментарий удален');
    }
}
