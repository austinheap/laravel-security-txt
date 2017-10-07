<?php

/**
 * security.txt
 */

Route::get('security.txt', [
    'as'    => 'security-txt.show',
    'uses'  => 'AustinHeap\Security\Txt\SecurityTxtController@show',
]);
