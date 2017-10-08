<?php
/**
 * src/routes/security-txt.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

Route::get('/.well-known/security.txt', [
    'as'    => 'security-txt.show',
    'uses'  => 'AustinHeap\Security\Txt\SecurityTxtController@show',
]);
