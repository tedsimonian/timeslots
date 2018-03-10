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
                            <th>Event Type</th>
                            <th>Event Date</th>
                            <th>Event Time</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Date Created</th>
                            <th>Date Completed</th>
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
            window.transactions_table=$('#transactions').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/employee/get-transactions',
                responsive:true,
                sAutoWidth:true,
                order: [[ 2, "desc" ]],
                columns: [

                    {data: 'user', name: 'user',width:'20%'},
                    {data: 'event_type', name: 'event_type',width:'10%'},
                    {data: 'event_date', name: 'event_date',width:'10%'},
                    {data: 'event_time', name: 'event_time',width:'10%'},
                    {data: 'price', name: 'price',width:'10%'},
                    {data: 'status', name: 'status',width:'10%'},
                    {data: 'date_created', name: 'date_created',width:'15%'},
                    {data: 'completed_at', name: 'completed_at',width:'15%'},



                ]
            });

            $('body').tooltip({
                selector: '.tooltipsy'
            });






        });


    </script>

@endsection



