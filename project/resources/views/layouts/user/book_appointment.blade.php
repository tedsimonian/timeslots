@extends('layouts.main')

@section('content')

    @if (session('warning'))

        <div class="alert alert-warning alert-rounded"> <i class="ti-user"></i> {{ session('warning') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif

    @if (session('status'))

        <div class="alert alert-warning alert-rounded"> <i class="ti-user"></i> {{ session('status') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif
    <div id="app">
        <book-appointment ></book-appointment>
    </div>

@endsection

