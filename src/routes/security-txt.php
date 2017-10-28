<?php
/**
 * src/routes/security-txt.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 *
 * @version     v0.3.0
 */
Route::get('/.well-known/security.txt', '\AustinHeap\Security\Txt\SecurityTxtController@show')
     ->name('security.txt');

Route::get('/security.txt', function () {
    return redirect()->route('security.txt');
});
