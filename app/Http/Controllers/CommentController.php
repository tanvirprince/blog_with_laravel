<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store($post, Request $request){

    $this->validate($request,[
        'comment' => 'required'
    ]);

    $comment = new Comment();
    $comment->post_id = $post;
    $comment->user_id = Auth::id();
    $comment->comment = $request->comment;
    $comment->save();
    Toastr::success('Comment Successfully posted','Success');
    return redirect()->back();
        }
}
