<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        // Get the parameters
        $consumer = Config::get('vatsim.consumer_key');
        $signature_method = Config::get('vatsim.method');
        $timestamp = Carbon::now()->timestamp;
        $nonce = md5(microtime() . mt_rand());
        $callback = Config::get('vatsim.callback_url');
        $sig = Config::get('vatsim.sso_key');

        // Make the request
        $client = new Client();
        $result = $client->request('GET', 'https://cert.vatsim.net/sso/api/login_token?oauth_consumer_key=' . $consumer . '&oauth_signature_method=' . $signature_method . '&oauth_timestamp=' . $timestamp . '&oauth_nonce=' . $nonce . '&oauth_callback=' . $callback . '&oauth_signature=' . $sig);
        $res = $client->request('GET', 'https://cert.vatsim.net/sso/test.php');
        console.log($res->getBody());
        dd(json_decode($result->getBody()));

    }
}
