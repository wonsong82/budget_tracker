<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];




    public function setSetting($key, $value)
    {
        $setting = $this->settings()->where('key', $key)->first();
        if($setting){
            $setting->fill(compact('value'))->save();
        }
        else {
            $setting = $this->settings()->create(compact('key', 'value'));
        }

        return $setting;
    }


    public function getSetting($key)
    {
        $setting = $this->settings()->where('key', $key)->first();

        return $setting? $setting->value : null;
    }







    public function token()
    {
        return $this->hasOne(UserToken::class, 'user_id');
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class, 'user_id');
    }
}
