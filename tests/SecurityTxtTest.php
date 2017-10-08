<?php
/**
 * tests/SecurityTxtTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.2.5
 */

use AustinHeap\Security\Txt\SecurityTxt;

/**
 * SecurityTxtTest
 */
class SecurityTxtTest extends \PHPUnit\Framework\TestCase
{

    public function testPlaceholder()
    {
        $this->assertEquals('', '');
    }

}
