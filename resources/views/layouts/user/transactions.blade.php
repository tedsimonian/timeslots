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

                            <th>Employee</th>
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


@endsection

@section('scripts')

    <script src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.1.1/js/responsive.bootstrap.min.js"></script>
    <script src="https://checkout.stripe.com/checkout.js"></script>
    <script>

        $(document).ready(function() {
            window.transactions_table=$('#transactions').DataTable({
                processing: true,
                serverSide: true,
                ajax: '/user/get-transactions',
                responsive:true,
                sAutoWidth:true,
                order: [[ 2, "desc" ]],
                columns: [

                    {data: 'employee', name: 'employee',width:'15%'},
                    {data: 'event_date', name: 'event_date',width:'10%'},
                    {data: 'event_time', name: 'event_time',width:'10%'},
                    {data: 'price', name: 'price',width:'5%'},
                    {data: 'status', name: 'status',width:'10%'},
                    {data: 'date_created', name: 'date_created',width:'15%'},
                    {data: 'completed_at', name: 'completed_at',width:'15%'},
                    {data: 'action', name: 'action',width:'10%'},


                ]
            });




            let amount=0;
            let event_id=0;
            // checkout handler
            let handler = StripeCheckout.configure({
                key: '{{$stripeKey}}',
                image: 'https://cdn.meme.am/images/100x100/15882140.jpg',
                token: function(token) {

                    let data = {
                        _token:'{{csrf_token()}}',
                        pay_later:true,
                        token: token,
                        amount:amount,
                        event_id:event_id
                    };

                    $.post("/user/complete-event",data,
                        function(data) {

                            if(data.success){


                                $.toast({
                                    heading: 'Appointment',
                                    text: data.message,
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'success',
                                    hideAfter: 4500,
                                    stack: 6
                                });

                                transactions_table.ajax.reload();

                            }else{

                                $.toast({
                                    heading: 'Appointment',
                                    text: 'Error booking an Appointment!',
                                    position: 'top-center',
                                    loaderBg: '#ff6849',
                                    icon: 'error',
                                    hideAfter: 4500,
                                    stack: 6
                                });
                            }


                        }).fail(function(data) {
                        $.toast({
                            heading: 'Appointment',
                            text: 'Error booking an Appointment!',
                            position: 'top-center',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 4500,
                            stack: 6
                        });
                    })

                }
            });

            $('body').on('submit', '.stripe-form',function(e) {



                    e.preventDefault();

                    amount=parseInt($(this).find('.amount').val());
                    event_id=$(this).find('.event_id').val();
                    handler.open({
                        name: 'Shut up and take my money!',
                        amount: Math.round(  $(this).find('.amount').val()),
                    });


            });



        });


    </script>

@endsection



