<?php
/**
 * tests/HelpersTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
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
    }
}
