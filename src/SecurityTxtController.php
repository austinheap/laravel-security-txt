<?php
/**
 * src/SecurityTxtController.php.
 *
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */
declare(strict_types=1);

namespace AustinHeap\Security\Txt;

use Response;

/**
 * SecurityTxtController.
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @link        https://packagist.org/packages/austinheap/laravel-security-txt
 * @link        https://austinheap.github.io/laravel-security-txt/classes/AustinHeap.Security.Txt.SecurityTxtController.html
 * @link        https://securitytext.org/
 */
class SecurityTxtController extends \Illuminate\Routing\Controller
{
    /**
     * Show the security.txt file.
     *
     * @return Response
     */
    public function show()
    {
        if (! config('security-txt.enabled', false)) {
            abort(404);
        }

        return Response::make(
            app('SecurityTxt')->fetch(),
            200,
            ['Content-Type' => 'text/plain']
        );
    }

    /**
     * Redirect to the proper location of the security.txt file.
     *
     * @return Response
     */
    public function redirect()
    {
        if (! config('security-txt.enabled', false)) {
            abort(404);
        }

        return redirect()->route('security.txt');
    }
}
