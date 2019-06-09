<?php

namespace App\Http\Controllers;

use App\User;
use App\SSO;
use Auth;
use Carbon\Carbon;
use Config;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        // Check and make sure they are not logged in
        if(!Auth::check()) {
            // Init the SSO object
            $sso = new SSO(Config::get('vatsim.base'), Config::get('vatsim.consumer_key'), Config::get('vatsim.secret'), Config::get('vatsim.method'), Config::get('vatsim.sso_key'), ['allow_suspended' => false, 'allow_inactive' => false]);
            return $sso->login(Config::get('vatsim.callback_url'), function ($key, $secret, $url) use ($request, $sso) {
                $request->session()->put("SSO", ['key' => $key, 'secret' => $secret]);
                $request->session()->put("SSO_OBJ", $sso);
                $request->session()->save();        // THIS *SHOULDN'T BE NEEDED!!!  But ... it is.
                return redirect($url);
            });
        } else {
            return redirect()->back()->with('error', 'You are already logged in.');
        }
    }

    public function completeLogin(Request $request) {
        // If the user wants to cancel, leave
        if(!$request->oauth_token) {
            $request->session()->forget("SSO");
            $request->session()->forget("return");
            return redirect('/')->with('error', 'No token found, login cancelled.');
        }

        // Get the SSO object
        $sso = $request->session()->get("SSO");

        // Make sure the session cookie is correct
        if(!isset($sso['key']) || !$sso['key'] ||
            !isset($sso['secret']) || !$sso['secret'])
            return redirect('error', 'There was an error with logging you in. Try restarting your browser and clearing your browser\'s cookies.');

        // Make sure the oauth verifier
        if(!$request->input('oauth_verifier'))
            return redirect('/')->with('error', 'Missing oauth verifier.');

        // Validate
        $sso_obj = $request->session()->get("SSO_OBJ");
        return $sso_obj->validate(
            $sso['key'],
            $sso['secret'],
            $request->input('oauth_verifier'),
            function($user) {
                session()->forget("SSO");
                session()->forget("return");

                // Check if the user has logged in once before
                $check_user = User::find($user->id);
                if($check_user) {
                    $check_user->fname = $user->name_first;
                    $check_user->lname = $user->name_last;
                    $check_user->email = $user->email;
                    $check_user->save();

                    $login_user = $check_user;
                } else {
                    $new_user = new User();
                    $new_user->id = $user->id;
                    $new_user->fname =$user->name_first;
                    $new_user->lname = $user->name_last;
                    $new_user->email = $user->email;
                    $new_user->is_ztl_staff = 0;
                    $new_user->save();

                    // Shouldn't be necessary to find the user, but it is
                    $login_user = User::find($user->id);
                }

                // Login
                Auth::login($login_user);

                return redirect('/')->with('success', 'You have been logged in successfully.');
            }
        );
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
