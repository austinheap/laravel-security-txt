<?php
/**
 * tests/TestCase.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

namespace AustinHeap\Security\Txt\Tests;

use AustinHeap\Security\Txt\SecurityTxtFacade;
use AustinHeap\Security\Txt\SecurityTxtServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ReflectionClass;

/**
 * TestCase
 */
class TestCase extends BaseTestCase
{
    protected $last_random;

    public static function callProtectedMethod($object, $method, array $args = [])
    {
        $class  = new ReflectionClass(get_class($object));
        $method = $class->getMethod($method);

        $method->setAccessible(true);

        return $method->invokeArgs($object, $args);
    }

    public function testTestCase()
    {
        $this->assertEquals('placeholder to silence phpunit warnings', 'placeholder to silence phpunit warnings');
    }

    protected function newRandomFromArray(array $array)
    {
        return $this->newRandom($array);
    }

    protected function newRandom($value)
    {
        if (is_array($value)) {
            $value = array_random($value);
        }

        $this->last_random = is_string($value) ? sprintf($value, (string)rand(1111, 9999)) : $value;

        return $this->last_random;
    }

    protected function currentRandom()
    {
        return $this->last_random;
    }

    protected function getPackageProviders($app)
    {
        return array_merge(parent::getPackageProviders($app), [SecurityTxtServiceProvider::class]);
    }

    protected function getPackageAliases($app)
    {
        return array_merge(parent::getPackageAliases($app), ['SecurityTxt' => SecurityTxtFacade::class]);
    }

}
