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
        $msg = FrontMsg::first();

        return view('site.home')->with('flights', $flights)->with('msg', $msg);
    }

    public function viewBooking($id) {
        // View own booking (if it exists)
    }

    public function addBooking($id) {
        $flight = Flight::find($id);
        $booking = $flight->book(Auth::id());

        return redirect('/bookings')->with('success', 'The booking was made successfully!');
    }

    public function removeBooking($id) {
        $flight = Flight::find($id);

        $success = $flight->unbook();

        if($success) {
            return redirect()->back()->with('success', 'Booking cancelled successfully!');
        } else {
            return redirect()->back()->with('error', 'The booking does not exist.');
        }
    }

    public function viewBookings() {
        // Get all bookings
        $flights = Flight::orderBy('dep_time')->get();
        
        return view('site.bookings')->with('flights', $flights);
    }

    public function updateFrontMessage(Request $request) {
        if(Auth::user()->isStaff()) {
            $front_msg = FrontMsg::first();
            $front_msg->content = $request->message;
            $front_msg->lastUpdatedBy = Auth::id();
            $front_msg->save();

            return redirect('/')->with('success', 'The front page message has been updated.');
        } else {
            return redirect()->back()->with('error', 'You are not allowed to do that.');
        }
    }

    public function manageYourBooking() {
        if(Auth::user()->hasBooking()) {
            $booking = Booking::where('pilot_id', Auth::id())->first();
            $flight = Flight::find($booking->flight_id);

            return view('site.manage-your-booking');
        } else {
            return redirect('/bookings')->with('error', 'You must make a booking first!');
        }
    }
}
