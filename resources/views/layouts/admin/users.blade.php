@extends('layouts.main')

@section('styles')


    <link href="//cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet">


@endsection
@section('content')

    <div class="row">

        <div class="col-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Users</h4>
                </div>
                <div class="card-body">

                    <a href="/admin/add-user"  class="btn btn-info text-white">+ Add new User</a>


                    <table class="display  table table-striped table-bordered table-hover responsive " id="users_table" width="100%">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>

                        </tr>
                        </thead>
                    </table>



                </div>
            </div>
        </div>
    </div>
    <div class="modal fade in" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="deleteLabel">Confirm Delete</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                </div>

                <div class="modal-body">
                    <p>Please type in DELETE in order to delete this record!</p>
                    <input type="text" class="form-control" id="user_delete_typing">
                </div>

                <div class="modal-footer">
                    <a class="btn btn-danger waves-effect delete-record" style="color:white !important">Delete</a>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Cancel</button>

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
            window.users_table = $('#users_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/get-users',
                responsive: true,
                sAutoWidth: true,
                columns: [
                    {data: 'id', name: 'id', width: '10%'},
                    {data: 'first_name', name: 'first_name', width: '15%'},
                    {data: 'last_name', name: 'last_name', width: '15%'},
                    {data: 'email', name: 'email', width: '30%'},
                    {data: 'role', name: 'role', width: '20%'},
                    {data: 'action', name: 'action', width: '10%'}
                ]
            });


            $(document).on('click','.delete',function(e){

                e.preventDefault();
                $('#confirm-delete').modal('show');
                let url=$(this).attr('href');




                $('.delete-record').click(function() {

                    if($('#user_delete_typing').val()!='DELETE'){

                        $.toast({
                            heading: 'User',
                            text: 'Please type in DELETE in all caps!',
                            position: 'top-center',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });

                        return false;
                    }


                    $.ajax({
                        url: url,
                        data:{delete_text:$('#user_delete_typing').val()},
                        success: function (result) {
                            if (result.success) {

                                $.toast({
                                    heading: 'User',
                                    text: result.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 4500,
                                    stack: 6
                                });
                                window.users_table.ajax.reload();
                                $('#user_delete_typing').val('');


                            } else {

                                $.toast({
                                    heading: 'User',
                                    text: result.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 4500,
                                    stack: 6
                                });
                            }

                            $('#confirm-delete').modal('hide');


                        },
                        error: function (error) {

                            $.toast({
                                heading: 'User',
                                text: error,
                                position: 'top-center',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 4500,
                                stack: 6
                            });

                            $('#confirm-delete').modal('hide');
                        }
                    });

                });

            });

        });

    </script>

@endsection