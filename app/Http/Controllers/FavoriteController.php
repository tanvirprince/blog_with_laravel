<?php

namespace App\Http\Controllers;

use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * @param $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add($post){

        $user = Auth::user();
        $isFavorite = $user->favorite_posts()->where('post_id',$post)->count();

        if ($isFavorite == 0)
        {
            $user->favorite_posts()->attach($post);
            Toastr::success('You added the in your favorite list','success');
            return redirect()->back();
        }else{
            $user->favorite_posts()->detach($post);
            Toastr::success('Dislike','success');
            return redirect()->back();
        }

    }
}
