<?php

namespace App\Http\Controllers;

use App\User;
use Google_Client;
use Google_Service_Sheets;
use Illuminate\Http\Request;

class GoogleController extends Controller
{

    /*public function auth()
    {
        $authUrl = urldecode(request()->get('url'));

        return view('google.auth', compact('authUrl'));
    }

    public function handleAuth()
    {
        $authCode = request()->get('auth_code');

        $client = new Google_Client();
        $client->setApplicationName('Threeon Helper');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(base_path('credentials.json'));
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new \Exception(join(', ', $accessToken));
        }

        $user = auth()->user();
        if($user->token){
            $user->token->fill($accessToken)->save();
        }

        else {
            $user->token()->create($accessToken);
        }


        return redirect()->intended();
    }*/

    public function auth()
    {
        $authCode = request()->get('code');

        $client = new Google_Client();
        $client->setApplicationName('Threeon Helper');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(base_path('credentials.json'));
        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

        // Check to see if there was an error.
        if (array_key_exists('error', $accessToken)) {
            throw new \Exception(join(', ', $accessToken));
        }

        $user = auth()->user();
        if($user->token){
            $user->token->fill($accessToken)->save();
        }

        else {
            $user->token()->create($accessToken);
        }


        return redirect()->intended();
    }






    public function parseGoogleSheetId($url)
    {
        $id = $url;

        $match = null;
        if(preg_match('#spreadsheets/d/([a-zA-Z\d-_]+)(/(edit?.+)?)?$#', $id, $match)){
            $id = $match[1];
        }

        return $id;
    }


    public function getGoogleClient()
    {
        $user = auth()->user();

        $client = new Google_Client();
        $client->setApplicationName('Threeon Helper');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(base_path('credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        if($user->token){
            $client->setAccessToken($user->token->toArray());
        }

        if($client->isAccessTokenExpired()){
            if($client->getRefreshToken()){
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            }

            else {
                $client->requireAuth = true;
                return $client;
            }

            if($user->token){
                $user->token->fill($client->getAccessToken())->save();
            }
            else {
                $user->token()->create($client->getAccessToken());
            }
        }

        return $client;
    }
}
