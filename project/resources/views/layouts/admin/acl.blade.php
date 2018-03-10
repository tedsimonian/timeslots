@extends('layouts.main')

@section('styles')


    <link href="//cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet">


@endsection
@section('content')

    <div class="row">
        <div class="col-3">

        </div>
        <div class="col-6">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Roles</h4>
                </div>
                <div class="card-body">


                    <table class="display  table table-striped table-bordered table-hover responsive " id="acl_table" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Role</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                    </table>



                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')

    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script>

        $(document).ready(function() {
            window.books_table = $('#acl_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/get-acl',
                responsive: true,
                sAutoWidth: true,
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'role', name: 'role', width: '80%'},
                    {data: 'action', name: 'action', width: '10%'}
                ]
            });

        });

    </script>

@endsection