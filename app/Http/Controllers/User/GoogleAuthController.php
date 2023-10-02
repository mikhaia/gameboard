<?php

namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Auth\AuthManager;
use App\Models\User;
use Google_Client;
use Google_Service_Oauth2;

final class GoogleAuthController
{
    public function __invoke(
        Request $request,
        Redirector $redirector,
        AuthManager $authManager
    ) {
        $clientID = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_SECRET');
        $redirectUri = env('GOOGLE_REDIRECT');
        
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

        if ($request->code) {
            $token = $client->fetchAccessTokenWithAuthCode($request->code);
            if (!isset($token['access_token'])) {
                return redirect('/')->withErrors(['msg' => $token['error_description']]);
            }
           
            $client->setAccessToken($token['access_token']);

            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
          
            $authManager->login(
                $this->create($google_account_info),
                true
            );
            return $redirector->to('/')->with('success', 'Happy to see you here!');
        } else {
            return $redirector->to($client->createAuthUrl());
        }
    }

    private function create($user) {
        return User::firstOrCreate([
            'google_id' => $user->id,
        ], [
            'name' => $user->givenName?$user->givenName:$user->name,
            'avatar' => $user->picture,
            'email' => $user->email,
            'password' => ''
        ]);
    }
}