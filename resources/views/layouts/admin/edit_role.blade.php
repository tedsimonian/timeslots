@extends('layouts.main')


@section('content')

    <div class="row">
        <div class="col-2">
        </div>
        <div class="col-8">
            <div class="card card-outline-info">
                <div class="card-header">
                    <h4 class="m-b-0 text-white">Edit Role</h4>
                </div>
                <div class="card-body">

                    <div id="app">

                        <edit-role id="{{$id}}"></edit-role>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection

