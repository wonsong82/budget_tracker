<?php

namespace App\Http\Middleware;

use App\Http\Controllers\GoogleController;
use Closure;

class GoogleClientAuth
{
    protected $googleController;

    public function __construct(GoogleController $googleController)
    {
        $this->googleController = $googleController;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @internal param GoogleController $googleController
     */
    public function handle($request, Closure $next)
    {
        $client = $this->googleController->getGoogleClient();

        if($client->requireAuth ?? null){
            session()->put('url.intended', url()->current());
            //$authUrl = urlencode($client->createAuthUrl());
            //return redirect()->to(route('google.auth.view') . '?url=' . $authUrl);
            $authUrl = $client->createAuthUrl();
            return redirect()->to($authUrl);
        }


        return $next($request);
    }
}
