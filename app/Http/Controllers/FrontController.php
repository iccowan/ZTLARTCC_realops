<?php

namespace App\Http\Controllers;

use Auth;
use App\FrontMsg;
use App\Booking;
use App\Flight;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function home() {
        // Get all of the flights
        $flights = Flight::orderBy('dep_time')->paginate(100);

        // Get the front page message
        $msg = FrontMsg::find(1);

        return view('site.home')->with('flights', $flights)->with('msg', $msg);
    }

    public function viewBooking($id) {
        // View own booking (if it exists)
    }

    public function addBooking($id) {
        $flight = Flight::find($id);
        if($flight->isBooked()) {
           return redirect('/bookings')->with('error', 'That flight is already booked.');
        } elseif(Auth::user()->hasBooking()) {
            return redirect('/bookings')->with('error', 'You already have a booking. Please cancel your booking to create a new one.');
        }

        // If the flight isn't booked and the user does not have a booking, book the flight
        $flight->book(Auth::id());

        return redirect('/manage-booking')->with('success', 'The booking was made successfully!');
    }

    public function removeBooking() {
        $booking = Booking::where('pilot_id', Auth::id())->first();
        if($booking) {
            $flight = Flight::find($booking->flight_id);
        } else {
            return redirect('/')->with('error', 'The booking does not exist.');
        }

        $success = $flight->unbook();

        if($success) {
            return redirect('/')->with('success', 'Booking cancelled successfully!');
        } else {
            return redirect('/')->with('error', 'The booking does not exist.');
        }
    }

    public function viewBookings() {
        // Get all bookings
        $flights_dep = Flight::where('departure', 'KATL')->orderBy('dep_time')->get();
        $flights_arr = Flight::where('arrival', 'KATL')->orderBy('dep_time')->get();
        
        return view('site.bookings')->with('flights_dep', $flights_dep)->with('flights_arr', $flights_arr);
    }

    public function updateFrontMessage(Request $request) {
        if(Auth::user()->isStaff()) {
            $front_msg = FrontMsg::find(1);
            $front_msg->content = $request->message;
            $front_msg->lastUpdatedBy = Auth::id();
            $front_msg->save();

            return redirect('/')->with('success', 'The front page message has been updated.');
        } else {
            return redirect()->back()->with('error', 'You are not allowed to do that.');
        }
    }

    public function manageYourBooking() {
        if(Auth::user()->isStaff() && !Auth::user()->hasBooking()) {
            $booking = Booking::where('pilot_id', Auth::id())->first();
            if($booking) {
                $flight = Flight::find($booking->flight_id);
            } else {
                $flight = null;
            }
            $message = FrontMsg::find(2);

            return view('site.manage-your-booking')->with('flight', $flight)->with('message', $message);
        } elseif(Auth::user()->hasBooking()) {
            $booking = Booking::where('pilot_id', Auth::id())->first();
            $flight = Flight::find($booking->flight_id);
            $message = FrontMsg::find(2);

            return view('site.manage-your-booking')->with('flight', $flight)->with('message', $message);
        } else {
            return redirect('/bookings')->with('error', 'You must make a booking first!');
        }
    }

    public function updateEcMessage(Request $request) {
        if(Auth::user()->isStaff()) {
            $front_msg = FrontMsg::find(2);
            $front_msg->content = $request->message;
            $front_msg->lastUpdatedBy = Auth::id();
            $front_msg->save();

            return redirect('/manage-booking')->with('success', 'The EC message has been updated.');
        } else {
            return redirect()->back()->with('error', 'You are not allowed to do that.');
        }
    }
}
