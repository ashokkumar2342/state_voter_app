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
            'localhost','127.0.0.1','164.100.200.54','edmshry.nic.in',$this->allSubdomainsOfApplicationUrl(),
        ];
    
    }
}