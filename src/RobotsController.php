<?php

namespace InfusionWeb\Laravel\Robots;

use App\Http\Controllers\Controller;

use Response;

class RobotsController extends Controller
{

    public function document()
    {
        if (config('robots.allow', false)) {
            // If allowed, serve a nice, welcoming robots.txt.
            Robots::addUserAgent('*');
            Robots::addSitemap('sitemap.xml');
        } else {
            // If not, tell everyone to go away.
            Robots::addDisallow('*');
        }

        return Response::make(Robots::generate(), 200, ['Content-Type' => 'text/plain']);
    }
}
