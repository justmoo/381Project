@extends('eo.layout.auth')
@section('content')
<div class="container text-center">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Dashboard</h1></div>

                <div class="panel-body">
                <h4>You are logged in as Eo!</h4>
                        <br><br><br><br>
                <a href="{{route('events.create')}}" class="btn btn-primary btn-lg">Create new event</a>
                


                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
