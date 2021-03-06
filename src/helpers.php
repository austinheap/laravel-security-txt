<?php
/**
 * src/helpers.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */
declare(strict_types=1);

if (! function_exists('securitytxt')) {
    /**
     * @return \AustinHeap\Security\Txt\SecurityTxtHelper
     */
    function securitytxt()
    {
        return \AustinHeap\Security\Txt\SecurityTxtServiceProvider::getInstance();
    }
}
