@extends('layouts.master')

@section('title')
    Home
@endsection

@section('content')
    <div class="jumbotron" style="background-image:url(/photos/ZTL_Banner.jpg); background-size:cover; background-repeat:no-repeat;">
        <div class="container">
            <div class="jumbotron" style="padding-bottom:20">
                <div class="row">
                    <div class="col-sm-8">
                        <h1><b>Atlanta Real Ops Booker</b></h1>
                        <h5>Welcome the the Atlanta ARTCC Real Ops Booker! This is intended for the event that is scheduled for June, 2019. Book a flight now! For simulation use only.</h5>
                    </div>
                    <div class="col-sm-4">
                        <a href="/bookings" class="btn btn-outline-primary btn-block">Book a Flight!</a>
                        <br>
                        <a href="https://www.ztlartcc.org" class="btn btn-outline-primary btn-block">Main ZTL Website</a>
                        @if(Auth::check() && Auth::user()->hasBooking())
                            <br>
                            <a href="/manage-booking" class="btn btn-outline-primary btn-block">Manage your Booking</a>
                        @endif
                    </div>
                </div>
                <br><br>
            </div>
            <center>
                <img src="/photos/banner.png" alt="Event banner">
            </center>
        </div>
    </div>
    <br>
    <div class="container">
        <center><h3>Announcement</h3></center>
        <div class="alert alert-info">
            <h5>{!! nl2br($msg->content) !!}</h5>
            <hr>
            <p><small><i>Last edited by</i> {{ App\User::find($msg->lastUpdatedBy)->full_name  }}</small></p>
            @if(Auth::check() && Auth::user()->isStaff())
                <button type="button" class="btn btn-success btn-small" data-toggle="modal" data-target="#editMsg">
                    Edit
                </button>

                {{-- Modal to edit the message --}}
                <div class="modal fade" id="editMsg" tabindex="-1" role="dialog" aria-labelledby="editMsg" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Update Message</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="/frontmessage/update" accept-charset="UTF-8">
                                    @csrf
                                    <div class="field">
                                        <textarea  class="form-control" name="message" rows="20">{!!  $msg->content  !!}</textarea>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Message</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <br>
        <hr>
        <br>
        <center><h3>Flights</h3></center>
        <div class="table table-wrapper-scroll-y my-custom-scrollbar">
            <table class="table table-bordered">
                <thead>
                    <th><center>Callsign</center</th>
                    <th><center>Departure Airport</center></th>
                    <th><center>Arrival Airport</center></th>
                    <th><center>Departure Time (Zulu)</center></th>
                    <th><center>Arrival Time (Zulu)</center></th>
                    <th><center>Book Now</center></th>
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
                            @elseif(Auth::check() && Auth::user()->hasBooking())
                                <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm disabled">Book Now!</a></center></td>
                            @else
                                <td><center><a href="/book/{{ $f->id }}" class="btn btn-success btn-sm">Book Now!</a></center></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script>
@endsection