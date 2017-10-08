<?php
/**
 * src/SecurityTxtController.php
 *
 * @package     laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

declare(strict_types=1);

namespace AustinHeap\Security\Txt;

/**
 * SecurityTxtController
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxtController.html
 */
class SecurityTxtController extends \App\Http\Controllers\Controller
{

    /**
     * Show the security.txt file.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        if (! config('security-txt.enabled', false))
            abort(404);

        return \Response::make(
                    (new SecurityTxt)->getText(true, true),
                    200,
                    [
                        'Content-Type' => 'text/plain',
                    ]
                );
    }

}
