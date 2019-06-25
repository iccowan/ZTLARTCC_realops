@extends('layouts.master')

@section('title')
    Your Booking
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Manage Your Booking</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        @if($flights)
            @foreach($flights as $flight)
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h3>Flight Details</h3>
                            </div>
                            <div class="card-body">
                                <p><b>Callsign:</b> {{ $flight->callsign }}</p>
                                <p><b>Departure Airport:</b> {{ $flight->departure }}</p>
                                <p><b>Arrival Airport:</b> {{ $flight->arrival }}</p>
                                <p><b>Departure/Arrival Time (Zulu):</b> {{ $flight->dep_time_formatted }} - {{ $flight->arr_time_formatted }}</p>
                                <p><b>Recommended Route of Flight:</b></p>
                                <p>{{ $flight->flight_plan }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-header">
                                <h3>Booking Actions</h3>
                            </div>
                            <div class="card-body">
                                <a href="/booking/remove/{{ $flight->id }}" class="btn btn-info btn-block">Cancel your Booking</a>
                                <a href="mailto:ec@ztlartcc.org" class="btn btn-info btn-block">Questions? Email the EC</a>
                                <hr>
                                <h5>Thank you for signing up to fly in the real ops event! We look forward to having you fly within the Atlanta airspace!</h5>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
                <br>
            @endforeach
        @else
            <div class="row">
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-header">
                            <h3>Flight Details</h3>
                        </div>
                        <div class="card-body">
                            <p><i>No flight booked; you must be a staff.</i></p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <br>
        <div class="card">
            <div class="card-header">
                <h3>Notes from the EC</h3>
            </div>
            <div class="card-body">
                {!! nl2br($message->content) !!}
                <hr>
                <p><small>Last Updated by {{ \App\User::find($message->lastUpdatedBy)->full_name }}</small></p>
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
                                    <form method="POST" action="/ecmessage/update" accept-charset="UTF-8">
                                        @csrf
                                        <div class="field">
                                            <textarea  class="form-control" name="message" rows="20">{!!  $message->content  !!}</textarea>
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
        </div>
        {{-- Pilot Briefing --}}
        <br>
        <div class="card">
            <div class="card-header">
                <h3>Pilot Briefing</h3>
            </div>
            <div class="card-body">
                <center>
                    <embed src="https://drive.google.com/viewerng/viewer?embedded=true&url=https://realops.ztlartcc.org/files/pilotBrief.pdf" width="1000px" height="800px" />
                </center>
            </div>
        </div>
    </div>

    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        })
    </script>
@endsection
