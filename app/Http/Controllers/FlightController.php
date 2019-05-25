<?php

namespace App\Http\Controllers;

use Auth;
use App\Booking;
use App\Email;
use App\Flight;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function addFlight() {
        if(Auth::user()->isStaff()) {
            return view('site.new-flight');
        } else {
            return redirect()->back()->with('error', 'You must be staff to do this.');
        }
    }

    public function storeFlight(Request $request) {
        if(Auth::user()->isStaff()) {
            // Validate
            $request->validate([
                'callsign' => 'required|unique:flights',
                'departure' => 'required',
                'arrival' => 'required',
                'dep_time' => 'required',
                'arr_time' => 'required',
                'flt_plan' => 'required'
            ]);

            // Create the flight
            $flight = new Flight();
            $flight->callsign = $request->callsign;
            $flight->departure = $request->departure;
            $flight->arrival = $request->arrival;
            $flight->dep_time = Carbon::parse($request->dep_time);
            $flight->arr_time = Carbon::parse($request->arr_time);
            $flight->flight_plan = $request->flt_plan;
            $flight->save();

            return redirect('/booking/manage/' . $flight->id)->with('success', 'The flight was added successfully!');
        } else {
            return redirect()->back()->with('error', 'You must be staff to do this.');
        }
    }

    public function manageFlight($id) {
        $flight = Flight::find($id);
        $booking = Booking::where('flight_id', $id)->first();

        return view('site.manage-flight')->with('flight', $flight)->with('booking', $booking);
    }

    public function editFlight($id) {
        if(Auth::user()->isStaff()) {
            $flight = Flight::find($id);
            return view('site.edit-flight')->with('flight', $flight);
        } else {
            return redirect()->back()->with('error', 'You must be staff to do this.');
        }
    }

    public function updateFlight(Request $request, $id) {
        if(Auth::user()->isStaff()) {
            $request->validate([
                'departure' => 'required',
                'arrival' => 'required',
                'dep_time' => 'required',
                'arr_time' => 'required',
                'flt_plan' => 'required'
            ]);

            $flight = Flight::find($id);
            $flight->departure = $request->departure;
            $flight->arrival = $request->arrival;
            $flight->dep_time = Carbon::parse($request->dep_time);
            $flight->arr_time = Carbon::parse($request->arr_time);
            $flight->flight_plan = $request->flt_plan;
            $flight->save();

            return redirect('/booking/manage/' . $id)->with('success', 'The flight was updated successfully!');
        } else {
            return redirect()->back()->with('error', 'You must be staff to do this.');
        }
    }

    public function deleteFlight($id) {
        if(Auth::user()->isStaff()) {
            $flight = Flight::find($id);
            $booking = Booking::where('flight_id', $id)->first();
            $flight->delete();

            // If the flight was booked, delete the booking
            if($booking) {
                $pilot = User::find($booking->pilot_id);
                $booking->delete();

                // Send the pilot an email
                $email = new Email();
                $email->email_address = $pilot->email;
                $email->subject = 'The flight that you booked has been removed';
                $email->view = 'emails.flight-removed';
                $email->sent = 0;
                $email->save();
            }

            return redirect('/bookings')->with('success', 'The flight has been deleted successfully and the pilot has been notified (if the flight was booked).');
        } else {
            return redirect()->back()->with('error', 'You must be staff to do this.');
        }
    }

    public function removeBooking($id) {
        if(Auth::user()->isStaff()) {
            $booking = Booking::where('flight_id', $id)->first();
            if($booking) {
                // Send the pilot an email letting them know their booking was removed
                $pilot = User::find($booking->pilot_id);
                $email = new Email();
                $email->email_address = $pilot->email;
                $email->subject = 'Your booking has been removed';
                $email->view = 'emails.booking-removed';
                $email->sent = 0;
                $email->save();

                $booking->delete();

                return redirect('/bookings')->with('success', 'The booking was removed successfully.');
            } else {
                return redirect()->back()->with('error', 'That flight is not booked.');
            }
        } else {
            return redirect()->back()->with('error', 'You aren\'t allowed to do that.');
        }
    }
}
