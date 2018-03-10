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
                    <h4 class="m-b-0 text-white">Events</h4>
                </div>
                <div class="card-body">


                    <table class="display  table table-striped table-bordered table-hover responsive" id="events" width="100%">
                        <thead>
                        <tr>


                            <th>Event Date</th>
                            <th>Event Time</th>
                            <th>Title</th>
                            <th>Color</th>
                            <th>Description</th>

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
            window.events_table=$('#events').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/employee/get-custom-events',
                responsive:true,
                sAutoWidth:true,
                order: [[ 0, "desc" ]],
                columns: [


                    {data: 'event_date', name: 'event_date',width:'15%'},
                    {data: 'event_time', name: 'event_time',width:'15%'},
                    {data: 'title', name: 'title',width:'15%'},
                    {data: 'color', name: 'color',width:'5%'},
                    {data: 'description', name: 'description',width:'50%'},


                ]
            });

            $('body').tooltip({
                selector: '.tooltipsy'
            });






        });


    </script>

@endsection



