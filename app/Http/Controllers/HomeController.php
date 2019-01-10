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


        return view('home', compact('spreadsheetId'));
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
