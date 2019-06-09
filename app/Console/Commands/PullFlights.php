<?php

namespace App\Console\Commands;

use App\RwFlight;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Config;

class PullFlights extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RWFlights:Pull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls real world flights from the FlightAware FlightXML API.';

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
        // Make sure the time is during the event to save API queries
        $time_now_exact = Carbon::now();
        $today = date('N');
        if($today == '6') {
            $time_now = new Carbon('6/29/2019 ' . $time_now_exact->toTimeString());
        } elseif($today == '7') {
            $time_now = new Carbon('6/30/2019 ' . $time_now_exact->toTimeString());
        } else {
            $time_now = $time_now_exact;
        }
        $event_start = new Carbon('6/29/2019 21:59');
        $event_end = new Carbon('6/30/2019 03:01');

        // Bool checked
        $goingToRun = $time_now->between($event_start, $event_end);

        // Only run if it's during event times
        if($goingToRun) {
            $client = new Client();
            $res = $client->request('GET', 'https://flightxml.flightaware.com/json/FlightXML3/AirportBoards?airport_code=' . Config::get('flights.airport_icao'), [
                'auth' => [
                    Config::get('flights.username'), Config::get('flights.api_key')
                ]
            ]);
            $result = json_decode($res->getBody(), true);
            $today = date('N');

            // Get the arrivals
            foreach ($result['AirportBoardsResult']['arrivals']['flights'] as $r) {
                try {
                    $flight = RwFlight::where('code', $r['ident'])->first();
                    if ($flight) {
                        $flight->depicao = $r['origin']['code'];
                        $flight->arricao = $r['destination']['code'];
                        $flight->route = $r["route"];
                        $flight->tailnum = $r['tailnumber'];
                        $flight->flightlevel = $r['filed_altitude'];
                        $flight->deptime = Carbon::createFromTimestamp($r['filed_departure_time']['epoch'])->toDateTimeString();
                        $flight->arrtime = Carbon::createFromTimestamp($r['filed_arrival_time']['epoch'])->toDateTimeString();
                        $flight->flighttime = $flighttime = gmdate('H:i', $r['filed_ete']);
                        if (strpos($flight->daysofweek, $today) === false)
                            $flight->daysofweek = $flight->daysofweek . $today;
                        $flight->save();
                    } else {
                        $flight = new RwFlight();
                        $flight->code = $r['ident'];
                        $flight->depicao = $r['origin']['code'];
                        $flight->arricao = $r['destination']['code'];
                        $flight->route = $r["route"];
                        $flight->tailnum = $r['tailnumber'];
                        $flight->flightlevel = $r['filed_altitude'];
                        $flight->deptime = Carbon::createFromTimestamp($r['filed_departure_time']['epoch'])->toDateTimeString();
                        $flight->arrtime = Carbon::createFromTimestamp($r['filed_arrival_time']['epoch'])->toDateTimeString();
                        $flight->flighttime = $flighttime = gmdate('H:i', $r['filed_ete']);
                        $flight->daysofweek = $today;
                        $flight->save();
                    }
                } catch(Exception $e) {
                    // Do nothing
                }
            }

            // Get the departures as well
            // Cleaner ways, but this works
            foreach ($result['AirportBoardsResult']['departures']['flights'] as $r) {
                try {
                    $flight = RwFlight::where('code', $r['ident'])->first();
                    if ($flight) {
                        $flight->depicao = $r['origin']['code'];
                        $flight->arricao = $r['destination']['code'];
                        $flight->route = $r['route'];
                        $flight->tailnum = $r['tailnumber'];
                        $flight->flightlevel = $r['filed_altitude'];
                        $flight->deptime = Carbon::createFromTimestamp($r['filed_departure_time']['epoch'])->toDateTimeString();
                        $flight->arrtime = Carbon::createFromTimestamp($r['filed_arrival_time']['epoch'])->toDateTimeString();
                        $flight->flighttime = $flighttime = gmdate('H:i', $r['filed_ete']);
                        if (strpos($flight->daysofweek, $today) === false)
                            $flight->daysofweek = $flight->daysofweek . $today;
                        $flight->save();
                    } else {
                        $flight = new RwFlight();
                        $flight->code = $r['ident'];
                        $flight->depicao = $r['origin']['code'];
                        $flight->arricao = $r['destination']['code'];
                        $flight->route = $r['route'];
                        $flight->tailnum = $r['tailnumber'];
                        $flight->flightlevel = $r['filed_altitude'];
                        $flight->deptime = Carbon::createFromTimestamp($r['filed_departure_time']['epoch'])->toDateTimeString();
                        $flight->arrtime = Carbon::createFromTimestamp($r['filed_arrival_time']['epoch'])->toDateTimeString();
                        $flight->flighttime = $flighttime = gmdate('H:i', $r['filed_ete']);
                        $flight->daysofweek = $today;
                        $flight->save();
                    }
                } catch(Exception $e) {
                    // Do nothing
                }
            }
        }
    }
}
