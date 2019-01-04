<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Http\Requests\SettingsRequest;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        if(!$user->getSetting('spreadsheetId')){
            return redirect()->route('view.settings');
        }


        return view('home');
    }






    public function auth()
    {
        $authCode = request()->get('auth_code');

        $client = new Google_Client();
        $client->setApplicationName('Spreadsheet Tracker');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(base_path('credentials.json'));

        $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
        //$client->setAccessToken($accessToken);

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


        return redirect()->route('home');
    }






    protected function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Spreadsheet Tracker');
        $client->setScopes([Google_Service_Sheets::SPREADSHEETS]);
        $client->setAuthConfig(base_path('credentials.json'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');


        $user = auth()->user();

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


    public function viewSettings()
    {
        $spreadsheetId = auth()->user()->getSetting('spreadsheetId');

        return view('settings', compact('spreadsheetId'));
    }


    public function saveSettings(SettingsRequest $request)
    {
        auth()->user()->setSetting('spreadsheetId', $request['spreadsheet_id']);

        return redirect()->route('home');
    }



    public function saveExpense(ExpenseRequest $request)
    {

        $client = $this->getClient();

        if(($client->requireAuth ?? null)){
            $authUrl = $client->createAuthUrl();
            return view('auth', compact('authUrl'));
        }

        $user = auth()->user();
        $spreadsheetId = $user->getSetting('spreadsheetId');

        if(!$spreadsheetId){
            return redirect()->route('view.settings');
        }

        $service = new Google_Service_Sheets($client);


        /*
         * Read
         *
        $range = 'RAW_DATA!A2:D';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();
        */



        /*
         * Write
         */

        $range = 'RAW_DATA!A2:D';
        $values = [
            array_values($request->only('date', 'desc', 'amt', 'type'))
        ];

        $body = new Google_Service_Sheets_ValueRange(compact('values'));
        $params = ['valueInputOption' => 'RAW'];
        $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);

        if($result->getUpdates()->getUpdatedCells()){
            Alert::success('Successfully added.')->flash();
            return redirect()->route('home');
        }
        else {
            Alert::error('Whoops, something has gone wrong.')->flash();
            return redirect()->route('home')->withInput();
        }
    }


}
