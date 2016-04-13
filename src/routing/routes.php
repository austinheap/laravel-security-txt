<?php

/**
 * Robots.txt
 */
Route::get('robots.txt', [
    'as' => 'robots.document',
    'uses' => 'RobotsController@document'
]);
