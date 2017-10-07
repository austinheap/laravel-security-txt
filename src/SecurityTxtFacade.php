<?php

namespace AustinHeap\Security\Txt;

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
