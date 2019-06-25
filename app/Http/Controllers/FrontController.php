<?php

namespace App\Http\Controllers;

use Auth;
use App\Email;
use App\FrontMsg;
use App\Booking;
use App\Flight;
use Carbon\Carbon;
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

    public function addBooking($id) {
        $flight = Flight::find($id);
        if($flight->isBooked()) {
           return redirect('/bookings')->with('error', 'That flight is already booked.');
        } elseif(Auth::user()->hasBooking() && !Auth::user()->canBookAnother($id)) {
            return redirect('/bookings')->with('error', 'You already have a booking. Please cancel your booking to create a new one.');
        }

        // If the flight isn't booked and the user does not have a booking, book the flight
        $flight->book(Auth::id());

        // Email a confirmation email
        $email = new Email();
        $email->email_address = Auth::user()->email;
        $email->subject = '[Confirmation] Thank you for booking a flight for the ZTL Real Ops Event';
        $email->view = 'emails.flight-booked';
        $email->sent = 0;
        $email->save();

        return redirect('/manage-booking')->with('success', 'The booking was made successfully!');
    }

    public function removeBooking($id) {
        $booking = Booking::where('pilot_id', Auth::id())->where('flight_id', $id)->first();
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

    public function viewBookings(Request $request) {
        $sort = $request->sort;

        if($sort) {
            if($sort == 'airline') {
                $airline = $request->airline;
                $flights_dep = Flight::where('departure', 'KATL')->where('callsign', 'LIKE', $airline . '%')->orderBy('dep_time')->get();
                $flights_arr = Flight::where('arrival', 'KATL')->where('callsign', 'LIKE', $airline . '%')->orderBy('dep_time')->get();
            } elseif($sort == 'dep_time') {
                $start = $request->start_time;
                $end = $request->end_time;
                $start_hr = intval(substr($start, 0, 2));
                $end_hr = intval(substr($end, 0, 2));

                // Convert the times to timestamps
                if($start_hr >= 22)
                    $start_stamp = new Carbon('2019-06-29 ' . $start);
                else
                    $start_stamp = new Carbon('2019-06-30 ' . $start);

                if($end_hr >= 22)
                    $end_stamp = new Carbon('2019-06-29 ' . $end);
                else
                    $end_stamp = new Carbon('2019-06-30 ' . $end);

                // Get flights with those parameter
                $flights_dep = Flight::where('departure', 'KATL')->where('dep_time', '>=', $start_stamp)->where('dep_time', '<=', $end_stamp)->orderBy('dep_time')->get();
                $flights_arr = Flight::where('arrival', 'KATL')->where('dep_time', '>=', $start_stamp)->where('dep_time', '<=', $end_stamp)->orderBy('dep_time')->get();
            } elseif($sort == 'arr_time') {
                $start = $request->start_time;
                $end = $request->end_time;
                $start_hr = intval(substr($start, 0, 2));
                $end_hr = intval(substr($end, 0, 2));

                // Convert the times to timestamps
                if($start_hr >= 22)
                    $start_stamp = new Carbon('2019-06-29 ' . $start);
                else
                    $start_stamp = new Carbon('2019-06-30 ' . $start);

                if($end_hr >= 22)
                    $end_stamp = new Carbon('2019-06-29 ' . $end);
                else
                    $end_stamp = new Carbon('2019-06-30 ' . $end);

                // Get flights with those parameter
                $flights_dep = Flight::where('departure', 'KATL')->where('arr_time', '>=', $start_stamp)->where('arr_time', '<=', $end_stamp)->orderBy('dep_time')->get();
                $flights_arr = Flight::where('arrival', 'KATL')->where('arr_time', '>=', $start_stamp)->where('arr_time', '<=', $end_stamp)->orderBy('dep_time')->get();
            } elseif($sort == 'length') {
                $length = intval($request->time);
                if($length == 'NaN')
                    return redirect()->back()->with('error', 'The length of flight should be an integer hour value (1, 2, 3, etc).');

                // Get all flights +/- 1 hour of the requested length
                $length_start = $length - 0.5;
                $length_end = $length + 0.5;
                $flights_dep = Flight::where('departure', 'KATL')->where('flight_time', '>=', $length_start)->where('flight_time', '<=', $length_end)->orderBy('dep_time')->get();
                $flights_arr = Flight::where('arrival', 'KATL')->where('flight_time', '>=', $length_start)->where('flight_time', '<=', $length_end)->orderBy('dep_time')->get();
            }
        } else {
            // Get all bookings
            $flights_dep = Flight::where('departure', 'KATL')->orderBy('dep_time')->get();
            $flights_arr = Flight::where('arrival', 'KATL')->orderBy('dep_time')->get();
        }
        
        return view('site.bookings')->with('flights_dep', $flights_dep)->with('flights_arr', $flights_arr)->with('sort', $sort);
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
            $flights = null;
            $message = FrontMsg::find(2);

            return view('site.manage-your-booking')->with('flights', $flights)->with('message', $message);
        } elseif(Auth::user()->hasBooking()) {
            $booking = Booking::where('pilot_id', Auth::id())->get();
            $flights_unordered = array();
            $i = 0;
            foreach($booking as $b) {
                $f = Flight::find($b->flight_id);
                $flights_unordered[$i] = $f;
                $i++;
            }

            // Reorder the flights in order of departure time if more than 1
            if(count($flights_unordered) > 1) {
                $flights = array();
                $j = 0;
                $f1 = $flights_unordered[0];
                $f2 = $flights_unordered[1];
                if($f1->dep_time > $f2->dep_time) {
                    $flights = [$f2, $f1];
                } else {
                    $flights = [$f1, $f2];
                }
            } else {
                $flights = $flights_unordered;
            }
            $message = FrontMsg::find(2);

            return view('site.manage-your-booking')->with('flights', $flights)->with('message', $message);
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

    public function viewBookedFlights(Request $request)
    {
        if(Auth::user()->isStaff()) {
            $booked_flights = Booking::get();
            $flights_dep = array();
            $flights_arr = array();
            $i_dep = 0;
            $i_arr = 0;

            foreach($booked_flights as $b) {
                $flight = Flight::find($b->flight_id);

                if($flight->departure == 'KATL') {
                    $flights_dep[$i_dep] = $flight;
                    $i_dep++;
                } elseif($flight->arrival == 'KATL') {
                    $flights_arr[$i_arr] = $flight;
                    $i_arr++;
                }
            }

            return view('site.booked-flights')->with('flights_dep', $flights_dep)->with('flights_arr', $flights_arr);
        } else {
            return redirect('/bookings');
        }
    }
}
