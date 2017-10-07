<?php

namespace AustinHeap\Security\Txt;

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
