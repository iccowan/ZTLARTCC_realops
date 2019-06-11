<?php

namespace App\Console\Commands;

use App\RwFlight;
use Config;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class GetRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Pull:FlightRoutes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull the routes for the flights that have been pulled';

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
        $flight = RwFlight::get();

        foreach($flight as $f) {
            if($f->route == null) {
                $res = $client->request('GET', 'https://flightxml.flightaware.com/json/FlightXML3/RoutesBetweenAirports?origin=' . $f->depicao . '&destination=' . $f->arricao, [
                    'auth' => [
                        Config::get('flights.username'), Config::get('flights.api_key')
                    ]
                ]);
                $result = json_decode($res->getBody());

                $f->route = $result->RoutesBetweenAirportsResult->data[0]->route;
                $f->save();
                // Sleep for 2 seconds to prevent exceeding the query limit
                sleep(2);
            }
        }
    }
}
