<?php

namespace App\Http\Middleware;

use App\Models\LogFrontEnd as LogFrontEndModel;
use Closure;

class LogFrontEnd
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $log = new LogFrontEndModel;
        $log->ip_address = $request->ip();
        $log->agent = $request->userAgent();
        $log->request_path = $request->path();
        $log->request_uri = $request->url();
        $log->request_full_uri = $request->fullUrl();

        $log->save();

        return $response;
    }
}
