<?php

namespace App\Http\Controllers;

class KlearcomDemoController extends Controller
{
    public function ivrConsole()
    {
        return response()
            ->view('klearcom.ivr-console')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate');
    }
}
