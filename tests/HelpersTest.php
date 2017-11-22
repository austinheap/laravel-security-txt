<?php
/**
 * tests/HelpersTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * HelpersTest
 */
class HelpersTest extends TestCase
{
    public function testHelperFunctionValid()
    {
        $this->assertEquals(function_exists('securitytxt'), true);
        $this->assertEquals(defined('LARAVEL_SECURITY_TXT_HELPERS_LOOP'), false);
    }

    public function testHelperFunctionInvalid()
    {
        $this->assertEquals(function_exists('securitytxt'), true);
        $this->assertEquals(defined('LARAVEL_SECURITY_TXT_HELPERS_LOOP'), false);

        require_once __DIR__ . '/../src/helpers.php';

        $this->assertEquals(defined('LARAVEL_SECURITY_TXT_HELPERS_LOOP'), true);
    }
}
