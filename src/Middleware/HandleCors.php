<?php

namespace Zdk\Cors\Middleware;

use Closure;
use Zdk\Cors\Services\CorsService;

class HandleCors
{
    private $cors;

    public function __construct(CorsService $corsService)
    {
        $this->cors = $corsService;
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
        if (!$this->cors->isCorsRequest($request)) {
            return $next($request);
        }

        if ($this->cors->isPreflightRequest($request)) {
            return $this->cors->handlePreflightRequest($request);
        }

        $response = $next($request);
        $this->cors->addActualResponse($request, $response);

        return $response;
    }
}