@extends('layouts.master')

@section('title')
    Bookings
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Bookings</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <div class="table">
            <table class="table table-bordered">
                <thead>
                <th><center>Callsign</center</th>
                <th><center>Departure Airport</center></th>
                <th><center>Arrival Airport</center></th>
                <th><center>Departure Time (Zulu)</center></th>
                <th><center>Arrival Time (Zulu)</center></th>
                <th><center>Book Now</center></th>
                @if(Auth::user()->isStaff())
                    <th>Actions</th>
                @endif
                </thead>
                <tbody>
                @foreach($flights as $f)
                    <tr>
                        <td><center>{{ $f->callsign }}</center></td>
                        <td><center>{{ $f->departure }}</center></td>
                        <td><center>{{ $f->arrival }}</center></td>
                        <td><center>{{ $f->dep_time_formatted }}</center></td>
                        <td><center>{{ $f->arr_time_formatted }}</center></td>
                        @if($f->isBooked())
                            <td><center><a class="btn btn-danger btn-sm disabled">Already Booked</a></center></td>
                        @elseif(Auth::user()->hasBooking())
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm disabled">Book Now!</a></center></td>
                        @else
                            <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm">Book Now!</a></center></td>
                        @endif
                        @if(Auth::user()->isStaff())
                            <td>
                                <div class="form-group">
                                    <center>
                                        <a href="/booking/manage/{{ $f->id  }}" class="btn btn-primary simple-tooltip" data-toggle="tooltip" title="Manage"><i class="fas fa-pencil-alt"></i></a>
                                    </center>
                                </div>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
