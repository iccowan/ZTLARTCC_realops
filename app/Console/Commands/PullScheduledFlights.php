<?php

namespace App\Console\Commands;

use App\RwFlight;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class PullScheduledFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:FlightSchedules';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls flight schedules for the real ops event.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        // Do departures
        $res = $client->request('GET', 'https://flightxml.flightaware.com/json/FlightXML3/AirlineFlightSchedules?start_date=1561847400&end_date=1561863600&origin=KATL&howMany=900', [
            'auth' => [
                Config::get('flights.username'), Config::get('flights.api_key')
            ]
        ]);
        $result = json_decode($res->getBody());

        // Create a flight
        foreach($result->AirlineFlightSchedulesResult->flights as $r) {
            $flight = RwFlight::where('code', $r->ident)->first();
            if($flight == null)
                $flight = RwFlight::where('code', $r->actual_ident)->first();
            if($flight == null) {
                $flight = new RwFlight();
                $flight->code = $r->ident;
                if($r->actual_ident != '')
                    $flight->code = $r->actual_ident;
                $flight->depicao = $r->origin;
                $flight->arricao = $r->destination;
                $flight->route = null;
                $flight->tailnum = null;
                $flight->flightlevel = null;
                $dep_time = Carbon::createFromTimestamp($r->departuretime)->toDateTimeString();
                $arr_time = Carbon::createFromTimestamp($r->arrivaltime)->toDateTimeString();
                $flight->deptime = $dep_time;
                $flight->arrtime = $arr_time;
                $arr_time = Carbon::parse($arr_time);
                $dep_time = Carbon::parse($dep_time);
                $flighttime = $arr_time->diffInSeconds($dep_time);
                $flighttime = gmdate('H:i', $flighttime);
                $flight->flighttime = $flighttime;
                $flight->daysofweek ='7';
                $flight->save();
            }
        }

        // Sleep for 1 minute and 1 second to allow for the queries to reset
        sleep(60);

        // Do arrivals
        $res = $client->request('GET', 'https://flightxml.flightaware.com/json/FlightXML3/AirlineFlightSchedules?start_date=1561840200&end_date=1561863600&destination=KATL&howMany=900', [
            'auth' => [
                Config::get('flights.username'), Config::get('flights.api_key')
            ]
        ]);
        $result = json_decode($res->getBody());

        // Create a flight
        foreach($result->AirlineFlightSchedulesResult->flights as $r) {
            $flight = RwFlight::where('code', $r->ident)->first();
            if($flight == null)
                $flight = RwFlight::where('code', $r->actual_ident)->first();
            if($flight == null && $r->arrivaltime >= 1561845600 && $r->arrivaltime <= 1561863600) {
                $flight = new RwFlight();
                $flight->code = $r->ident;
                if($r->actual_ident != '')
                    $flight->code = $r->actual_ident;
                $flight->depicao = $r->origin;
                $flight->arricao = $r->destination;
                $flight->route = null;
                $flight->tailnum = null;
                $flight->flightlevel = null;
                $dep_time = Carbon::createFromTimestamp($r->departuretime)->toDateTimeString();
                $arr_time = Carbon::createFromTimestamp($r->arrivaltime)->toDateTimeString();
                $flight->deptime = $dep_time;
                $flight->arrtime = $arr_time;
                $arr_time = Carbon::parse($arr_time);
                $dep_time = Carbon::parse($dep_time);
                $flighttime = $arr_time->diffInSeconds($dep_time);
                $flighttime = gmdate('H:i', $flighttime);
                $flight->flighttime = $flighttime;
                $flight->daysofweek ='7';
                $flight->save();
            }
        }
    }
}
