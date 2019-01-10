<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsRequest;
use App\User;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function viewSettings()
    {
        $spreadsheetId = $this->getSetting('spreadsheetId');

        return view('settings', compact('spreadsheetId'));
    }


    public function saveSettings(SettingsRequest $request, GoogleController $googleController)
    {
        $id = $googleController->parseGoogleSheetId($request['spreadsheet_id']);

        $this->setSetting('spreadsheetId', $id);

        return redirect()->route('home');
    }





    public function setSetting($key, $value)
    {
        $user = auth()->user();

        $setting = $user->settings()->where('key', $key)->first();
        if($setting){
            $setting->fill(compact('value'))->save();
        }
        else {
            $setting = $user->settings()->create(compact('key', 'value'));
        }

        return $setting;
    }


    public function getSetting($key)
    {
        $user = auth()->user();

        $setting = $user->settings()->where('key', $key)->first();

        return $setting? $setting->value : null;
    }



}
