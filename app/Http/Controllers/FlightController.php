<?php

namespace App\Http\Controllers;

use App\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function viewFlights() {
        $flights = Flight::orderBy('dep_time')->get();

        return view('')->with('flights', $flights);
    }

    public function addFlight(Request $request) {
        // Create new flight
    }

    public function storeFlight(Request $request) {
        // Save new flight
    }

    public function editFlight($id) {
        // Edit flight
    }

    public function updateFlight(Request $request, $id) {
        // Update the flight
    }

    public function deleteFlight(Request $request) {
        // Delete flight
    }
}
