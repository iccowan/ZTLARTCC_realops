<?php

namespace App;

use Carbon\Carbon;
use App\Booking;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flights';

    // Check to see if a booking exists for the flight
    public function isBooked() {
        $booking = Booking::where('flight_id', $this->id)->first();

        if($booking) {
            $is_booked = true;
        } else {
            $is_booked = false;
        }

        return $is_booked;
    }

    // Books the flight
    // Inputs:
    //  int $pilot_id
    // Outputs:
    //  Booking $booking
    public function book($pilot_id) {
        // Create a new booking
        $booking = new Booking();
        $booking->pilot_id = $pilot_id;
        $booking->flight_id = $this->id;
        $booking->save();

        return $booking;
    }

    // Un-books the flight
    // Inputs:
    //  NONE
    // Outputs:
    //  boolean $success
    public function unbook() {
        // Get the booking
        $booking = Booking::where('flight_id', $this->id);

        // Make sure the booking actually exists
        if($booking) {
            $booking->delete();
            $success = true;
        } else {
            $success = false;
        }

        return $success;
    }

    // Format the departure time
    public function getDepTimeFormattedAttribute() {
        $time = Carbon::parse($this->dep_time)->format('H:i');

        return $time;
    }

    // Format the arrival time
    public function getArrTimeFormattedAttribute() {
        $time = Carbon::parse($this->arr_time)->format('H:i');

        return $time;
    }

    // Format dep time for editing
    public function getDepTimeEditAttribute() {
        $time = Carbon::parse($this->dep_time)->format('m/d/Y H:i');

        return $time;
    }

    // Format arr time for editing
    public function getArrTimeEditAttribute() {
        $time = Carbon::parse($this->arr_time)->format('m/d/Y H:i');

        return $time;
    }

    // Get the name of the booked pilot
    public function getPilotNameAttribute() {
        $booking = Booking::where('flight_id', $this->id)->first();
        $pilot = User::find($booking->pilot_id);

        return $pilot->full_name;
    }

    // Get the CID of the booked pilot
    public function getPilotCidAttribute() {
        $booking = Booking::where('flight_id', $this->id)->first();

        return $booking->pilot_id;
    }
}
