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
                        @if(!Auth::check())
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
@endsection