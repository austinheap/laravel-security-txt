<?php

/**
 * Robots.txt
 */
Route::get('robots.txt', [
    'as' => 'robots.document',
    'uses' => 'InfusionWeb\Laravel\Robots\RobotsController@document'
]);
