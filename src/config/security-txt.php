<?php
/**
 * src/config/security-txt.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

return [

    'enabled'           => env('SECURITY_TXT_ENABLED', false),

    'debug'             => env('SECURITY_TXT_DEBUG', false),

    'cache'             => env('SECURITY_TXT_CACHE', false),

    'cache-time'        => env('SECURITY_TXT_CACHE_TIME', 5),

    'cache-key'         => env('SECURITY_TXT_CACHE_KEY', 'cache:AustinHeap\Security\Txt\SecurityTxt'),

    'comments'          => env('SECURITY_TXT_COMMENTS', true),

    'contacts'          => env('SECURITY_TXT_CONTACT', false) !== false ?
                           [env('SECURITY_TXT_CONTACT')] : null,

    'encryption'        => env('SECURITY_TXT_ENCRYPTION', null),

    'disclosure'        => env('SECURITY_TXT_DISCLOSURE', null),

    'acknowledgement'   => env('SECURITY_TXT_ACKNOWLEDGEMENT', null),

];
