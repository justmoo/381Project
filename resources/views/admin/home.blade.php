@extends('admin.layout.auth')

@section('content')
<div class="container text-center">
    <div class="row">
        <div class="col-md-12 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading text-center">Admin Dashboard</div>

                <div class="panel-body ">
                 <h3>   You are logged in as <strong>Admin</strong>!</h3>
                
                <table class="table text-center">
                        <thead>
                        <tr>
                                <td>name</td>
                                <td>type</td> 
                                <td>takes place</td>
                                <td>tickets</td>
                                <td>approve</td>
                                <td>delete</td>

                        </tr>
                    </thead>
                            <tbody>
                        @foreach($events as $event)
                        <tr>
                                <td><a class="link" href="{{ url('/events') }}/{{$event->id}}">{{$event->name}}</a></td>
                                <td>{{$event->type}}</td> 
                                <td>{{$event->takes_place_at->diffForHumans()}}
                                <td>{{$event->tickets}}</td>
                                <td>
                                    <form method="POST" action="{{ route('events.update', $event) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-primary btn-sm">approve</button>
                                    </form>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('events.destroy', $event) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm">delete</button>
                                    </form>
                                </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                Eo's table 
                soon to be here
                {{-- <table class="table text-center">
                        <thead>
                        <tr>
                                <td>name</td>
                                <td>email</td>

                                

                        </tr>
                    </thead>
                            <tbody>
                        @foreach($eo as $eo)
                        <tr>
                                <td>{{$eo->name}}</td>
                                <td>{{$eo->email}}</td>
                        
                        </tr>

                        @endforeach
                    </tbody>
                </table> --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
