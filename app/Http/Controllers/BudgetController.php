<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

class BudgetController extends Controller
{
    public function index(SettingController $setting)
    {
        $spreadsheetId = $setting->getSetting('spreadsheetId');

        if(!$spreadsheetId){
            return redirect()->route('view.settings');
        }

        return view('home', compact('spreadsheetId'));
    }



    public function saveExpense(ExpenseRequest $request, GoogleController $google, SettingController $setting)
    {
        $client = $google->getGoogleClient();
        $spreadsheetId = $setting->getSetting('spreadsheetId');

        $service = new Google_Service_Sheets($client);


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


    public function showExpenses(GoogleController $google, SettingController $setting)
    {
        $client = $google->getGoogleClient();
        $spreadsheetId = $setting->getSetting('spreadsheetId');

        $service = new Google_Service_Sheets($client);

        /*
         * Read
         */
        $range = 'RAW_DATA!A2:D';
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        $expenses = collect($values)->reverse();

        return view('expenses', compact('expenses'));
    }
}
