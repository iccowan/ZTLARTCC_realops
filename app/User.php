<?php

namespace App;

use App\Booking;
use App\Flight;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'pilots';
    protected $secret = ['remember_token'];

    // Return full name: "fname lname"
    public function getFullNameAttribute() {
        $name = $this->fname . ' ' . $this->lname;

        return $name;
    }

    // Check to see if the user has a booking
    public function hasBooking() {
        $booking = Booking::where('pilot_id', $this->id)->first();

        if($booking) {
            $has = true;
        } else {
            $has = false;
        }

        return $has;
    }

    // Check for staff
    public function isStaff() {
        $staff = $this->is_ztl_staff;

        if($staff == 1) {
            $is_staff = true;
        } else {
            $is_staff = false;
        }

        return $is_staff;
    }

    public function canBookAnother($flight_id) {
        $bookings = Booking::where('pilot_id', $this->id)->get();
        if(count($bookings) >= 2)
            return false;

        // If the first passes, the pilot only has one booking or no bookings
        $booking = Booking::where('pilot_id', $this->id)->first();
        if($booking) {
            $booked_flight = Flight::find($booking->id);
            $proposed_flight = Flight::find($flight_id);
            $booked_deptime = new Carbon($booked_flight->dep_time);
            $booked_deptime = $booked_deptime->timestamp;
            $booked_arrtime = new Carbon($booked_flight->arr_time);
            $booked_arrtime = $booked_arrtime->timestamp;
            $proposed_deptime = new Carbon($proposed_flight->dep_time);
            $proposed_deptime = $proposed_deptime->timestamp;
            $proposed_arrtime = new Carbon($proposed_flight->arr_time);
            $proposed_arrtime = $proposed_arrtime->timestamp;

            // Don't allow 2 departures or 2 arrivals
            if ($booked_flight->departure == $proposed_flight->departure || $booked_flight->arrival == $proposed_flight->arrival)
                return false;

            // Don't allow the bookings to overlap
            if($booked_deptime == $proposed_deptime) {
                return false;
            } else {
                if($booked_deptime < $proposed_deptime) {
                    $f1 = $booked_arrtime;
                    $f2 = $proposed_deptime;
                } else {
                    $f2 = $booked_deptime;
                    $f1 = $proposed_arrtime;
                }

                if($f1 >= $f2)
                    return false;
            }
        }

        // If this gets here, either the flights don't overlap or the user doesn't have anything booked yet
        return true;
    }
}
