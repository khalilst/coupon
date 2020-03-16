<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    $routes = collect(app()->routes->getRoutes())->map(function ($route) {
        $middleware = collect($route->gatherMiddleware())->map(function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        })->implode(',');

        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'action' => ltrim($route->getActionName(), '\\'),
            'middleware' => $middleware,
        ];
    });
    return view('welcome', compact('routes'));
});
