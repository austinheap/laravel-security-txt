<?php
namespace EllisTheDev\Robots;

use Illuminate\Support\Facades\Facade;

/**
 * Class RobotsFacade
 *
 * @package EllisTheDev\Robots
 */
class RobotsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'robots';
    }
}
