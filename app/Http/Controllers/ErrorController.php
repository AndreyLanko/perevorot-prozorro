<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ErrorController extends BaseController
{
    public function notfound()
    {
        return response()->view('errors/404', [
            'html'=>app('App\Http\Controllers\PageController')->get_html()
        ], 404);
    }

    public function systemerror()
    {
        return view('errors/500')
                ->with('html', app('App\Http\Controllers\PageController')->get_html());
    }
}
