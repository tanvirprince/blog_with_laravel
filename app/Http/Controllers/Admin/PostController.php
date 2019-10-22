<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Notifications\AuthorPostApproved;
use App\Notifications\NewAuthorPost;
use App\Notifications\NewPostNotify;
use App\Post;
use App\Subscriber;
use App\Tag;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('admin.post.index',compact('posts'));
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
        return view('admin.post.create',compact('categories','tags'));
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
        $posts->is_approved = true;
        $posts->save();

        $posts->categories()->attach($request->categories);
        $posts->tags()->attach($request->tags);

        $subscribers = Subscriber::all();
        foreach ($subscribers as $subscriber)
        {
            Notification::route('mail',$subscriber->email)
                ->notify(new NewPostNotify($posts));
        }

        Toastr::success('Post Successfully saved:','Success');
        return redirect()->route('admin.post.index');





    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {

        return view('admin.post.show',compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {

        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit',compact('categories','tags','post'));
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
        $post->is_approved = true;
        $post->save();

        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Successfully Updated:','Success');
        return redirect()->route('admin.post.index');

    }




    public function pending(){

        $posts = Post::where('is_approved',false)->get();
        return view('admin.post.pending',compact('posts'));
    }


    public function approval($id){
        $posts = Post::find($id);
        if ($posts->is_approved == false)
        {
        $posts->is_approved = true;
        $posts->save();

        $posts->user->notify(new AuthorPostApproved($posts));


            $subscribers = Subscriber::all();
            foreach ($subscribers as $subscriber)
            {
                Notification::route('mail', $subscriber->email)
                    ->notify(new NewPostNotify($posts));


            }
            Toastr::success('Post has been Approved Successfully','Approved');

        }else{
            Toastr::info('This Post already approved','Info');

        }
        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (Storage::disk('public')->exists('post/'.$post->image))
        {
            Storage::disk('public')->delete('post/'.$post->image);
        }

        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();
        Toastr::Success('Post Succesfully Deleted','Success');
        return redirect()->back();

    }
}
