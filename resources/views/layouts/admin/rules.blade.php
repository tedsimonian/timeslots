@extends('layouts.main')




@section('content')


    <div id="app">

        <admin-rules id="{{$id}}"></admin-rules>
    </div>
@endsection



@section('scripts')


    <script>

        $(document).ready(function(){

            $("a[href='#rules']").click(function(){


                $('#rules').attr('aria-expanded',true);
                $('#working').attr('aria-expanded',false);
                $('#working').removeClass('active');
                $('#special').attr('aria-expanded',false);
                $('#special').removeClass('active');

            });


            $("a[href='#working']").click(function(){


                $('#working').attr('aria-expanded',true);
                $('#rules').attr('aria-expanded',false);
                $('#rules').removeClass('active');
                $('#special').attr('aria-expanded',false);
                $('#special').removeClass('active');

            });

            $("a[href='#special']").click(function(){


                $('#special').attr('aria-expanded',true);
                $('#rules').attr('aria-expanded',false);
                $('#rules').removeClass('active');
                $('#working').attr('aria-expanded',false);
                $('#working').removeClass('active');

            });

        });

    </script>

@endsection