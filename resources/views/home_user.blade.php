@extends('layouts.main')

@section('content')
    @if( app('request')->input('registered') )

        <div class="alert alert-success alert-rounded"> <i class="ti-user"></i> We sent you an activation code. Check your email and click on the link to verify.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>

    @endif
    @if (session('warning'))

        <div class="alert alert-warning alert-rounded"> <i class="ti-user"></i> {{ session('warning') }}
            Click <a href="/user/resend-email/{{session('user_id')}}">here</a> to resend the email.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif

    @if (session('resend'))

        <div class="alert alert-warning alert-rounded"> <i class="ti-user"></i>
            The verification email has been resent.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif

    @if (session('status'))

        <div class="alert alert-warning alert-rounded"> <i class="ti-user"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif

    @can('view user calendar')
        <div id="app">
            <user-calendar></user-calendar>
        </div>
    @endcan



@endsection
@section('scripts')

    <script>

        window.stripeKey='{{$stripeKey}}';


    </script>

@endsection

