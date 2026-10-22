<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlogAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->get('blog_auth')) {
            return redirect()->route('login')->with('error', 'Bu işlem için önce giriş yapmalısınız.');
        }
        return $next($request);
    }
}
