<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
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
        echo $res->getBody();
        dd(json_decode($sig);
    }

    public function testLogin(Request $request) {
        $type = $request->acct;

        if($type == 'pilot') {
            Auth::login(User::find(1371395));
        } elseif($type == 'staff') {
            Auth::login(User::find(1364926));
        }

        return redirect('/');
    }

    public function logout() {
        Auth::logout();

        return redirect('/')->with('success', 'You were logged out successfully.');
    }
}
