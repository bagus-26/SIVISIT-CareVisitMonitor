<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedOrigins = array_filter([
            'http://localhost',
            'http://127.0.0.1',
            'http://localhost:8000',
            'http://127.0.0.1:8000',
            'https://usivisit.gt.tc',
            'http://usivisit.gt.tc',
            env('APP_URL'),
        ]);

        $origin = $request->headers->get('Origin') ?? '';
        $response = $next($request);

        if ($origin) {
            if (in_array(rtrim($origin, '/'), $allowedOrigins)) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            } else {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
            }
        } else {
            $response->headers->set('Access-Control-Allow-Origin', '*');
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, Accept, X-Requested-With');
        $response->headers->set('Access-Control-Max-Age', '86400');

        if ($request->isMethod('OPTIONS')) {
            return response('', 200, $response->headers->all());
        }

        return $response;
    }
}
