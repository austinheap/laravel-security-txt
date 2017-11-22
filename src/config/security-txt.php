<?php
/**
 * src/config/security-txt.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

return [

    'enabled'           => env('SECURITY_TXT_ENABLED', false),

    'debug'             => env('SECURITY_TXT_DEBUG', false),

    'cache'             => env('SECURITY_TXT_CACHE', false),

    'cache-time'        => is_null(env('SECURITY_TXT_CACHE_TIME', null)) ?
                           5 : (int) env('SECURITY_TXT_CACHE_TIME'),

    'cache-key'         => env('SECURITY_TXT_CACHE_KEY', 'cache:AustinHeap\Security\Txt\SecurityTxt'),

    'comments'          => env('SECURITY_TXT_COMMENTS', true),

    'contacts'          => env('SECURITY_TXT_CONTACT', false) !== false ?
                           [env('SECURITY_TXT_CONTACT')] : null,

    'encryption'        => env('SECURITY_TXT_ENCRYPTION', null),

    'disclosure'        => env('SECURITY_TXT_DISCLOSURE', null),

    'acknowledgement'   => env('SECURITY_TXT_ACKNOWLEDGEMENT', null),

];
