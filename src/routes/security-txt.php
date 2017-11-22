<?php
/**
 * src/routes/security-txt.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */


Route::get('/.well-known/security.txt', '\AustinHeap\Security\Txt\SecurityTxtController@show')
     ->name('security.txt');

Route::get('/security.txt', '\AustinHeap\Security\Txt\SecurityTxtController@redirect')
     ->name('security.txt-redirect');
