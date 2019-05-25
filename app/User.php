<?php

namespace App;

use App\Booking;
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
}
