@extends('layouts.frontend.app')

@section('title','Tag Post')

@push('css')
    <link href="{{ asset('assets/frontend/all-post/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/all-post/responsive.css') }}" rel="stylesheet">


    <style>

        .favorite_posts{
            color: blue;
        }

    </style>


@endpush

@section('content')

    <div class="container">
        <div class="jumbotron text-center">
            <h1> Tag: {{ $tag->name }}</h1>

        </div>

    </div>







    <!-- slider -->

    <section class="blog-area section">
        <div class="container">

            <div class="row">
                @if($tag->posts->count() > 0)

                @foreach($tag->posts as $post)

                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100">
                            <div class="single-post post-style-1">

                                <div class="blog-image"><a href="{{ route('post.details',$post->slug) }}"><img src="{{ asset('/../storage/app/public/post/'.$post->image)}}" alt="Blog Image"></a></div>

                                <a class="avatar" href="{{ route('author.profile',$post->user->username) }}"><img src="{{ asset('/../storage/app/public/profile/'.$post->user->image)}}" alt="Profile Image"></a>

                                <div class="blog-info">

                                    <h4 class="title"><a href="{{ route('post.details',$post->slug) }}"><b>{{ $post->title }}</b></a></h4>

                                    <ul class="post-footer">

                                        <li>
                                            @guest
                                                <a href="javascript:void(0)" onclick="toastr.info('To Add Favorite List You need to login!','INFO', {closeButton: true, progressBar: true})">
                                                    <i class="ion-heart"></i>{{ $post->favorite_to_users->count() }}</a></li>
                                        @else

                                            <a href="javascript:void(0)" onclick="document.getElementById('favorite-form-{{ $post->id }}').submit();" class="{{ !Auth::user()->favorite_posts->where('pivot.post_id',$post->id)->count() == 0 ? 'favorite_posts' : '' }}">
                                                <i class="ion-heart"></i>{{ $post->favorite_to_users->count() }}</a></li>

                                            <form id="favorite-form-{{ $post->id }}" action="{{ route('post.favorite',$post->id) }}" method="post" style="display: none">
                                                @csrf

                                            </form>

                                        @endguest


                                        <li><a href="#"><i class="ion-chatbubble"></i>{{ $post->comments->count() }}</a></li>
                                        <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count }}</a></li>
                                    </ul>

                                </div><!-- blog-info -->
                            </div><!-- single-post -->
                        </div><!-- card -->
                    </div>


                @endforeach

                @else
                    <div class="breadcrumb">
                        Sorry No post found

                    </div>

                @endif

            </div><!-- row -->

{{--{{ $tag->links() }}--}}
        </div><!-- container -->
    </section><!-- section -->




@endsection

@push('js')




@endpush

