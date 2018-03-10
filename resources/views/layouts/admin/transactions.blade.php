@extends('layouts.main')


@section('styles')


    <link href="//cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css" rel="stylesheet">
    <style>
        .status{

            border-radius: 3px;
            padding: 3px;
            color:white;
        }

        .completed{

            background-color:green;
        }

        .pending{

            background-color:rgb(255, 178, 43)
        }

    </style>
@endsection

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Transactions</h4>
                </div>
                <div class="card-body">


                    <table class="display  table table-striped table-bordered table-hover responsive" id="transactions" width="100%">
                        <thead>
                        <tr>

                            <th>Client Name</th>
                            <th>Employee Name</th>
                            <th>Event Type</th>
                            <th>Event Date</th>
                            <th>Event Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Date Completed</th>
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
                    <input type="text" class="form-control" id="delete_typing">
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
            window.transactions_table=$('#transactions').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/admin/get-transactions',
                responsive:true,
                sAutoWidth:true,
                order: [[ 3, "desc" ]],
                columns: [

                    {data: 'user', name: 'user',width:'20%'},
                    {data: 'employee', name: 'employee',width:'15%'},
                    {data: 'event_type', name: 'event_type',width:'10%'},
                    {data: 'event_date', name: 'event_date',width:'10%'},
                    {data: 'event_time', name: 'event_time',width:'10%'},
                    {data: 'price', name: 'price',width:'10%'},
                    {data: 'status', name: 'status',width:'10%'},
                    {data: 'date_created', name: 'date_created',width:'15%'},
                    {data: 'completed_at', name: 'completed_at',width:'15%'},
                    {data: 'action', name: 'action',width:'5%'},


                ]
            });

            $('body').tooltip({
                selector: '.tooltipsy'
            });


            $(document).on('click','.delete',function(e){


                e.preventDefault();
                $('#confirm-delete').modal('show');
                let url=$(this).attr('href');




                $('.delete-record').click(function() {

                    if($('#delete_typing').val()!='DELETE'){

                        $.toast({
                            heading: 'Transaction',
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
                        data:{delete_text:$('#delete_typing').val()},
                        success: function (result) {
                            if (result.success) {

                                $.toast({
                                    heading: 'Transaction',
                                    text: result.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 4500,
                                    stack: 6
                                });
                                window.transactions_table.ajax.reload();
                                $('#delete_typing').val('');


                            } else {

                                $.toast({
                                    heading: 'Transaction',
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
                                heading: 'Transaction',
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



