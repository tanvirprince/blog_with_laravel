<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->approved()->published()->paginate(12);
        return view('posts',compact('posts'));

    }

    public function details($slug){

        $post = Post::where('slug', $slug)->first();
        $blogKey = 'blog_'.$post->id;
        if(!Session::has($blogKey)){
            $post->increment('view_count');
            Session::put($blogKey,1);

        }
        $randomposts = Post::approved()->published()->take(3)->inRandomOrder()->get();
        return view('post', compact('post','randomposts'));
    }


    public function postByCategory($slug)
    {
       $category = Category::where('slug',$slug)->first();
       $posts = $category->posts()->approved()->published()->get();
        return view('category_post',compact('category','posts'));

    }

    public function postByTag($slug)
    {
        $tag = Tag::where('slug',$slug)->first();
        return view('tag_post',compact('tag'));

    }
}
