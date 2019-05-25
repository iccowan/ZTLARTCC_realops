<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function addFlight(Request $request) {
        // Create new flight
    }

    public function storeFlight(Request $request) {
        // Save new flight
    }

    public function manageFlight($id) {
        $flight = Flight::find($id);
        $booking = Booking::where('flight_id', $id)->first();

        return view('site.manage-flight')->with('flight', $flight)->with('booking', $booking);
    }

    public function updateFlight(Request $request, $id) {
        // Update the flight
    }

    public function deleteFlight(Request $request) {
        // Delete flight
    }
}
