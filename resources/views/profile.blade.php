@extends('layouts.frontend.app')

@section('title','profile')


@push('css')
    <link href="{{ asset('assets/frontend/profile/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/profile/responsive.css') }}" rel="stylesheet">


    <style>

        .slider{ height: 300px; width: 100%;
            background-image: url(<?php echo e(asset('/assets/frontend/images/slider-1.jpg')); ?>);
            background-size: cover; }


        .favorite_posts{
            color: blue;
        }

    </style>


@endpush

@section('content')

    <div class="slider display-table center-text">
        <h1 class="title display-table-cell"><b> Name: {{ $author->name }}</b></h1>
    </div>


    <section class="blog-area section">
        <div class="container">

            <div class="row">

                <div class="col-lg-8 col-md-12">
                    <div class="row">

                        @if($posts->count() > 0)

                        @foreach($posts as $post)

                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="single-post post-style-1">

                                        <div class="blog-image"><img src="{{ asset('/storage/post/'.$post->image)}}" alt="Blog Image"></div>

                                        <a class="avatar" href="{{ route('author.profile',$post->user->username) }}"><img src="{{ asset('/storage/profile/'.$post->user->image)}}" alt="Profile Image"></a>

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
                            <div class="col-md-12 breadcrumb">
                                <h1>Sorry No post found</h1>

                            </div>
                        @endif

                    </div><!-- row -->

                    <a class="load-more-btn" href="#"><b>LOAD MORE</b></a>

                </div><!-- col-lg-8 col-md-12 -->

                <div class="col-lg-4 col-md-12 ">

                    <div class="single-post info-area ">

                        <div class="about-area">
                            <h4 class="title"><b>ABOUT ATHOR</b></h4>
                            <p> {{ $author->name }}</p>
                            <p> {{ $author->about }}</p><br/>
                            <h4><b> Author Since: {{ $author->created_at->toDateString() }}</b> </h4><br/>
                            <h4><b> Author Post: {{ $author->posts->count() }}</b> </h4><br/>

                        </div>





                    </div><!-- info-area -->

                </div><!-- col-lg-4 col-md-12 -->

            </div><!-- row -->

        </div><!-- container -->
    </section>






@endsection

@push('js')




@endpush

