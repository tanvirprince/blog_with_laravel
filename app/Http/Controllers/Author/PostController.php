<?php

namespace App\Http\Controllers\Author;

use App\Category;
use App\Notifications\NewAuthorPost;
use App\Post;
use App\Tag;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->get();
        return view('author.post.index',compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.create',compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'image' => 'required',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required'
        ]);


        $image = $request->file('image');
        $slug = str_slug($request->title);

        if (isset($image))
        {
            // make unique name for image

            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('post'))
            {
                Storage::disk('public')->makeDirectory('post');

            }

            $postImage = Image::make($image)->resize(1600,1066)->save($image->getClientOriginalExtension());  // change here function update

            Storage::disk('public')->put('post/'.$imageName,$postImage);

        }else{

            $imageName = "default.png";
        }

        $posts = new Post();
        $posts->user_id = Auth::id();
        $posts->title = $request->title;
        $posts->slug =  $slug;
        $posts->image =  $imageName;
        $posts->body = $request->body;

        if (isset($request->status))
        {
            $posts->status = true;
        }else{
            $posts->status = false;
        }
        $posts->is_approved = false;
        $posts->save();

        $posts->categories()->attach($request->categories);
        $posts->tags()->attach($request->tags);

        $users = User::where('role_id','1')->get();
        Notification::send($users, new NewAuthorPost($posts));

        Toastr::success('Post Successfully saved:','Success');
        return redirect()->route('author.post.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if ($post->user_id != Auth::id())
        {

            Toastr::error('you are not authorize to access this post','ERROR');
            return redirect()->back();
        }

        return view('author.post.show',compact('post'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if ($post->user_id != Auth::id())
        {

            Toastr::error('you are not authorize to access this post','ERROR');
            return redirect()->back();
        }

        $categories = Category::all();
        $tags = Tag::all();
        return view('author.post.edit',compact('categories','tags','post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {

        if ($post->user_id != Auth::id())
        {

            Toastr::error('you are not authorize to access this post','ERROR');
            return redirect()->back();
        }

        $this->validate($request,[
            'title' => 'required',
            'image' => 'image',
            'categories' => 'required',
            'tags' => 'required',
            'body' => 'required'
        ]);


        $image = $request->file('image');
        $slug = str_slug($request->title);

        if (isset($image))
        {
            // make unique name for image

            $currentDate = Carbon::now()->toDateString();
            $imageName = $slug.'-'.$currentDate.'-'.uniqid().'.'.$image->getClientOriginalExtension();

            if (!Storage::disk('public')->exists('post'))
            {
                Storage::disk('public')->makeDirectory('post');

            }

            // old photo delete
            if (Storage::disk('public')->exists('post/'.$post->image))
            {
                Storage::disk('public')->delete('post/'.$post->image);
            }



            $postImage = Image::make($image)->resize(1600,1066)->save($image->getClientOriginalExtension());  // change here function update

            Storage::disk('public')->put('post/'.$imageName,$postImage);

        }else{

            $imageName = $post->image;
        }


        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug =  $slug;
        $post->image =  $imageName;
        $post->body = $request->body;

        if (isset($request->status))
        {
            $post->status = true;
        }else{
            $post->status = false;
        }
        $post->is_approved = false;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Successfully Updated:','Success');
        return redirect()->route('author.post.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->user_id != Auth::id())
        {

            Toastr::error('you are not authorize to access this post','ERROR');
            return redirect()->back();
        }


        if (Storage::disk('public')->exists('post/'.$post->image))
        {
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
        Toastr::Success('Post Has Succesfully Deleted','Success');
        return redirect()->back();
    }
}
