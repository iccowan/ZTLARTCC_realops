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
        <center>
            <h3>Flights</h3>
            <a href="/bookings"><p>View All Flights!</p></a>
        </center>
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
        <br>
        <hr>
        <br>
        <center>
            <h3>Frequently Asked Questions</h3>
        </center>
        <br>
        <ul>
            <li>
                <b><small>Q: </small>Can I book a flight?</b>
                <br>
                <small>A: </small>Yes! As long as you are a registered VATSIM member, you can book up to one departure and one arrival during the event.
            </li>
            <br>
            <li>
                <b><small>Q: </small>How many flights can I book?</b>
                <br>
                <small>A: </small>You may book a total of TWO flights. One arrival and one departure. Please note that the arrival and departure times must be at least ten minutes apart in order to allow for time to turn the airplane.
            </li>
            <br>
            <li>
                <b><small>Q: </small>Why can I not book another flight?</b>
                <br>
                <small>A: </small>As stated above, you may only book one arrival flight and one departure flight with at least a ten minute gap between the arrival and departure times. If you are still having problems booking a flight, please let a member of the ZTL ARTCC staff know for assistance.
            </li>
            <br>
            <li>
                <b><small>Q: </small>How do I cancel my booking?</b>
                <br>
                <small>A: </small>To cancel a booking, click on your name in the upper right hand corner (after logging in) and click on "Manage your Bookings". From here, you can view all of the information regarding your booking(s) and cancel a booking if necessary.
            </li>
            <br>
            <li>
                <b><small>Q: </small>Am I required to fly the provided route?</b>
                <br>
                <small>A: </small>No, although it is highly recommended. All flights are real world flights with the most recent routes. We cannot force you to fly a route if you are unable, but it will help all of the controllers, and add to realism, if you file the suggested flight plan.
            </li>
            <br>
            <li>
                <b><small>Q: </small>Do I have to fly at the scheduled time?</b>
                <br>
                <small>A: </small>Again, no, although it is highly recommended. Our staff have checked the schedule to make sure there will be as few delays as possible with the available bookings so if you depart at your scheduled time, there will be much less waiting around prior to departure/landing.
            </li>
            <br>
            <li>
                <b><small>Q: </small>How can I get into contact with staff?</b>
                <br>
                <small>A: </small>If you have questions regarding the event, please email the events coordinator at <a href="mailto:ec@ztlartcc.org">ec@ztlartcc.org</a>. If you have issues with the website, please contact the webmaster at <a href="mailto:wm@ztlartcc.org">wm@ztlartcc.org</a>. Please direct all other questions/inquires to the air traffic manager at <a href="mailto:atm@ztlartcc.org">atm@ztlartcc.org</a>.
            </li>
            <br>
            <li>
                <b><small>Q: </small>I've never heard of VATSIM. What is this?</b>
                <br>
                <small>A: </small>VATSIM is an online simulation network for aviation enthusiasts around the world. This is in no way associated with any real-world air traffic centers or aviation companies. This is not our main website either. If you would like more information, check out <a href="www.vatsim.net">www.vatsim.net</a> or our website at <a href="www.ztlartcc.org">www.ztlartcc.org</a>. We hope to see you on the network!
            </li>
        </ul>
    </div>

    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script>
@endsection