@extends('layouts.backend.app')

@section('title','tag')

@push('css')
    <link href="{{ asset('/') }}assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css" rel="stylesheet">

@endpush

@section('content')

    <section class="content">

        <div class="container-fluid">
            <div class="block-header">
                <a class="btn btn-primary waves-effect" href="{{ route('admin.tag.create') }}">
                <i class="material-icons"> add </i>
                <span> Add New Tag </span>

                </a>
            </div>
            <!-- Basic Examples -->

            <!-- #END# Basic Examples -->
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                All TAG
                                <span class="badge bg-blue"> {{ $tags->count() }}</span>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="javascript:void(0);">Action</a></li>
                                        <li><a href="javascript:void(0);">Another action</a></li>
                                        <li><a href="javascript:void(0);">Something else here</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                    <tr>
                                        <th>ID </th>
                                        <th>Tag Name</th>
                                        <th> Post Count</th>
                                        <th>Created at</th>
                                        <th>updated at</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tfoot>
                                    <tr>
                                        <th>ID </th>
                                        <th>Tag Name</th>
                                        <th>Post Count</th>
                                        <th>Created at</th>
                                        <th>updated at</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                    <tbody>
                                    @foreach($tags as $key=>$tag)

                                    <tr>
                                        <td> {{ $key+1 }}</td>
                                        <td> {{ $tag->name }}</td>
                                        <td> {{ $tag->posts->count() }}</td>
                                        <td>{{ $tag->created_at }}</td>
                                        <td>{{ $tag->updated_at }}</td>
                                        <td class="text-center">
                                        <a href="{{route('admin.tag.edit',$tag)}}">
                                            <i class="material-icons btn btn-info waves-effect"> edit </i>
                                        </a>

                                            <button class="btn btn-danger waves-effect" type="submit"
                                            onclick="deleteTag({{ $tag->id }})">
                                            <i class="material-icons"> delete </i>
                                            </button>
                                            <form id="delete-form-{{$tag->id}}" action="{{ route('admin.tag.destroy',$tag->id) }}" method="post" style="display: none;">
                                                @CSRF
                                                @method('delete')


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
        function deleteTag(id) {

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
                    document.getElementById('delete-form-'+id).submit();

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
