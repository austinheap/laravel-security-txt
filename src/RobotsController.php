<?php

namespace InfusionWeb\Laravel\Robots;

use App\Http\Controllers\Controller;

use Response;

class RobotsController extends Controller
{

    public function document()
    {
        $robots = new Robots();

        if (config('robots.allow', false)) {
            // If allowed, serve a nice, welcoming robots.txt.
            $robots->addUserAgent('*');
            $robots->addSitemap('sitemap.xml');
        } else {
            // If not, tell everyone to go away.
            $robots->addDisallow('*');
        }

        return Response::make($robots->generate(), 200, ['Content-Type' => 'text/plain']);
    }
}
