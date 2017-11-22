<?php
/**
 * tests/FacadeTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

namespace AustinHeap\Security\Txt\Tests;

use AustinHeap\Security\Txt\SecurityTxt;
use AustinHeap\Security\Txt\SecurityTxtFacade;
use RuntimeException;
use SecurityTxt as SecurityTxtRealFacade;

/**
 * FacadeTest
 */
class FacadeTest extends TestCase
{

    public function testManualConstruct()
    {
        $facade = new SecurityTxtFacade();
        $this->assertEquals('SecurityTxt', $this->callProtectedMethod($facade, 'getFacadeAccessor'));
    }

    public function testConstructValid()
    {
        $this->assertEquals(app('SecurityTxt'), securitytxt());
    }

    public function testConstructInvalid()
    {
        $this->assertNotEquals(app('SecurityTxt'), new SecurityTxt());
    }

    public function testAccessor()
    {
        $this->assertEquals(self::callProtectedMethod(new SecurityTxtFacade(), 'getFacadeAccessor'), 'SecurityTxt');
    }

    public function testResetNotOverriden()
    {
        app('SecurityTxt')->disable();
        $this->assertEquals(false, app('SecurityTxt')->getEnabled());

        SecurityTxtRealFacade::enable(true);

        $this->assertEquals(true, app('SecurityTxt')->getEnabled());
    }

    public function testCallStaticInvalid()
    {
        $class = new class() extends SecurityTxtFacade
        {
            public static function getFacadeRoot()
            {
                return null;
            }
        };
        $this->expectException(RuntimeException::class);
        $class::staticMethodThatDoesntExist();
    }

}
