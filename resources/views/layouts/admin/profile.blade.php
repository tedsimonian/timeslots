@extends('layouts.main')


@section('content')

    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-8">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Profile </h4>
                </div>
                <div class="card-body">

                    <div id="app">

                        <admin-profile id="{{Auth()->user()->id}}"></admin-profile>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection

