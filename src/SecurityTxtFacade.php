<?php
/**
 * src/SecurityTxtFacade.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxtFacade
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxtFacade.html
 */
class SecurityTxtFacade extends \Illuminate\Support\Facades\Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'securitytxt';
    }

}
