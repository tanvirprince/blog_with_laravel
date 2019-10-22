@extends('layouts.backend.app')

@section('title', 'tag')

@push('css')
@endpush

@section('content')
    <section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>TAG</h2>
        </div>


        <!-- Vertical Layout | With Floating Label -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT NEW TAG
                        </h2>

                    </div>
                    <div class="body">
                        <form action="{{ route('admin.tag.update',$tag->id) }}" method="post">
                            @CSRF
                            @method('PUT')
                            <div class="form-group form-float">
                                <div class="form-line">
                                    <input type="text" id="name" class="form-control" name="name" value="{{ $tag->name }}">
                                    <label class="form-label"> Tag Name</label>
                                </div>
                            </div>



                            <a type="button" class="btn btn-danger m-t-15 waves-effect" href="{{ route('admin.tag.index') }}"> BACK </a>
                            <button type="submit" class="btn btn-primary m-t-15 waves-effect"> Update </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- #END# Multi Column -->
    </div>

    </section>

@endsection

@push('js')
@endpush
