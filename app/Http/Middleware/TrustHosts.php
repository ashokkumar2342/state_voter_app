<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

use Closure;
use Log;

class TrustHosts extends Middleware
{
    
    public function hosts()
    {
        return [
            'localhost','127.0.0.1','10.145.41.196',$this->allSubdomainsOfApplicationUrl(),
        ];
    
    }
}