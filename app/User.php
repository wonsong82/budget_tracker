<?php

namespace App;

use App\Http\Controllers\GoogleController;
use Google_Client;
use Google_Service_Sheets;
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






    public function token()
    {
        return $this->hasOne(UserToken::class, 'user_id');
    }

    public function settings()
    {
        return $this->hasMany(UserSetting::class, 'user_id');
    }
}
