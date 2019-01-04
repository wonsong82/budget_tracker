<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model
{
    protected $table = 'oauth_tokens';

    protected $fillable = [
        'user_id', 'access_token', 'expires_in', 'refresh_token', 'scope', 'token_type', 'created'
    ];

    protected $hidden = [
        'id', 'user_id', 'created_at', 'updated_at'
    ];


}
