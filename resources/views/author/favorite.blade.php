@extends('layouts.backend.app')

@section('title','Post')

@push('css')
    <link href="{{ asset('/') }}assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

@endpush

@section('content')

    <section class="content">


            <!-- Basic Examples -->

            <!-- #END# Basic Examples -->
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All Favorite Post
                                <span class="badge bg-blue"> {{ $posts->count() }}</span>
                            </h2>

                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                    <tr>
                                        <th>ID </th>
                                        <th> Title </th>
                                        <th> Author </th>
                                        <th> <i class="material-icons">favorite</i> </th>
{{--                                        <th> <i class="material-icons">comment  </i> </th>--}}
                                        <th> <i class="material-icons"> visibility </i></th>

                                        <th> Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>ID </th>
                                        <th> Title </th>
                                        <th> Author </th>
                                        <th> <i class="material-icons">favorite</i> </th>
                                        {{--                                        <th> <i class="material-icons">comment  </i> </th>--}}
                                        <th> <i class="material-icons"> visibility </i></th>

                                        <th> Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($posts as $key=>$post)

                                    <tr>
                                        <td> {{ $key+1 }}</td>
                                        <td> {{ str_limit($post->title,'18')  }}</td>
                                        <td> {{ $post->user->name }}</td>
                                        <td> {{ $post->favorite_to_users->count() }}</td>
                                        <td> {{ $post->view_count }}</td>


                                        <td>
                                            <button class="btn btn-info waves-effect">
                                                <i class="material-icons"> visibility </i>
                                            </button>
                                            <button class="btn btn-danger waves-effect" type="submit"
                                            onclick="removePost({{ $post->id }})">
                                            <i class="material-icons"> delete </i>
                                            </button>
                                            <form id="remove-form-{{$post->id}}" action="{{ route('post.favorite',$post->id) }}" method="post" style="display: none;">
                                                @CSRF

                                            </form>

                                        </td>

                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
        </div>


    </section>
@endsection

@push('js')
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/jquery.dataTables.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js"></script>

    <script src="{{ asset('/') }}assets/backend/js/pages/tables/jquery-datatable.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
    
    <script>
        function removePost(id) {

            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {

                    event.preventDefault();
                    document.getElementById('remove-form-'+id).submit();

                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your Data is safe :)',
                        'error'
                    )
                }
            })
        }

        
    </script>
@endpush
