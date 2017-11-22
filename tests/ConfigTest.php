<?php
/**
 * tests/ConfigTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * ConfigTest
 */
class ConfigTest extends TestCase
{
    public function testDefaultEnabled()
    {
        $this->assertEquals(false, app('config')->get('security-txt.enabled'));
    }

    public function testDefaultDebug()
    {
        $this->assertEquals(false, app('config')->get('security-txt.debug'));
    }

    public function testDefaultCache()
    {
        $this->assertEquals(false, app('config')->get('security-txt.cache'));
    }

    public function testDefaultCacheTime()
    {
        $this->assertEquals(5, app('config')->get('security-txt.cache-time'));
    }

    public function testDefaultCacheKey()
    {
        $this->assertEquals('cache:AustinHeap\Security\Txt\SecurityTxt', app('config')->get('security-txt.cache-key'));
    }

    public function testDefaultComments()
    {
        $this->assertEquals(true, app('config')->get('security-txt.comments'));
    }

    public function testDefaultContacts()
    {
        $this->assertNull(app('config')->get('security-txt.contacts'));
    }

    public function testDefaultEncryption()
    {
        $this->assertNull(app('config')->get('security-txt.encryption'));
    }

    public function testDefaultDisclosure()
    {
        $this->assertNull(app('config')->get('security-txt.disclosure'));
    }

    public function testDefaultAcknowledgement()
    {
        $this->assertNull(app('config')->get('security-txt.acknowledgement'));
    }

}
