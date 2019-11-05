<?php

namespace Sarfraznawaz2005\VisitLog\Middleware;

use Closure;
use Sarfraznawaz2005\VisitLog\Browser;
use Sarfraznawaz2005\VisitLog\VisitLog;

use Sarfraznawaz2005\VisitLog\Models\VisitLog as VisitModel;


class ipCheckMiddleware extends VisitLog
{

    protected $visitlog;

    public function __construct()
    {
        $this->visitlog = new VisitLog(new Browser);
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if( VisitModel::where('ip', $this->visitlog->getUserIP())->where('is_banned', 1)->first() )
            abort(404);

        return $next($request);
    }
}
