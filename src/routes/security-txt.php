<?php

/**
 * security.txt
 */

Route::get('/.well-known/security.txt', [
    'as'    => 'security-txt.show',
    'uses'  => 'AustinHeap\Security\Txt\SecurityTxtController@show',
]);
