@extends('layouts.master')

@section('title')
    Flight Management
@endsection

@section('content')
    <span class="border border-light" style="background-color:#F0F0F0">
    <div class="container">
        &nbsp;
        <h2>Manage Flight</h2>
        &nbsp;
    </div>
</span>
    <br>

    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Flight Details</h3>
                    </div>
                    <div class="card-body">
                        @if($flight->isBooked())
                            <p><b>Pilot Name:</b> {{ \App\User::find($booking->pilot_id)->full_name }}</p>
                            <p><b>Pilot CID:</b> {{ $booking->pilot_id }}</p>
                        @endif
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
                        <a href="/bookings" class="btn btn-info btn-block"><i class="fas fa-arrow-left"></i> Go Back</a>
                        @if($flight->isBooked())
                            <a href="/booking-manage/remove" class="btn btn-info btn-block">Cancel this Booking</a>
                            <a href="mailto:{{ \App\User::find($booking->pilot_id)->email }}" class="btn btn-info btn-block">Email the Pilot</a>
                        @endif
                        <a href="/booking-manage/edit" class="btn btn-info btn-block">Update this Flight</a>
                        <a href="/booking-manage/delete/{{ $flight->id }}" class="btn btn-danger btn-block">Delete this Flight</a>
                        <hr>
                        <h5>Thank you for signing up to fly in the real ops event! We look forward to having you fly within the Atlanta airspace!</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });
    </script>
@endsection
