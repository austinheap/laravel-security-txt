<?php
/**
 * tests/CacheTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * CacheTest
 */
class CacheTest extends DocumentTestCase
{
    public function testEnableCache()
    {
        app('SecurityTxt')->disableCache();
        $this->assertAttributeEquals(false, 'cache', app('SecurityTxt'));

        app('SecurityTxt')->enableCache();
        $this->assertAttributeEquals(true, 'cache', app('SecurityTxt'));

        app('SecurityTxt')->disableCache();
        $this->assertAttributeEquals(false, 'cache', app('SecurityTxt'));
    }

    public function testDisableCache()
    {
        app('SecurityTxt')->enableCache();
        $this->assertAttributeEquals(true, 'cache', app('SecurityTxt'));

        app('SecurityTxt')->disableCache();
        $this->assertAttributeEquals(false, 'cache', app('SecurityTxt'));

        app('SecurityTxt')->enableCache();
        $this->assertAttributeEquals(true, 'cache', app('SecurityTxt'));
    }

    public function testSetCache()
    {
        foreach ([false, true, false] as $value) {
            app('SecurityTxt')->setCache($value);
            $this->assertAttributeEquals($value, 'cache', app('SecurityTxt'));
        }
    }

    public function testGetCache()
    {
        foreach ([true, false, true] as $value) {
            app('SecurityTxt')->setCache($value);
            $this->assertAttributeEquals($value, 'cache', app('SecurityTxt'));
            $this->assertEquals($value, app('SecurityTxt')->getCache());
        }
    }

    public function testSetCacheKey()
    {
        app('SecurityTxt')->setCacheKey($this->newRandom(['unit-test-cache-key-1:%s', 'unit-test-cache-key-2:%s']));
        $this->assertAttributeEquals($this->currentRandom(), 'cacheKey', app('SecurityTxt'));
    }

    public function testGetCacheKey()
    {
        app('SecurityTxt')->setCacheKey($this->newRandom(['unit-test-cache-key-1:%s', 'unit-test-cache-key-2:%s']));
        $this->assertEquals($this->currentRandom(), app('SecurityTxt')->getCacheKey());
    }

    public function testSetCacheTime()
    {
        app('SecurityTxt')->setCacheTime($this->newRandom([1, 2, 3, 4, 5]));
        $this->assertAttributeEquals($this->currentRandom(), 'cacheTime', app('SecurityTxt'));
    }

    public function testGetCacheTime()
    {
        app('SecurityTxt')->setCacheTime($this->newRandom([1, 2, 3, 4, 5]));
        $this->assertEquals($this->currentRandom(), app('SecurityTxt')->getCacheTime());
    }

    public function testCache()
    {
        $disabled = app('SecurityTxt')->disableCache()
                                      ->clearCache()
                                      ->fetch();

        $this->assertContains('# Cache is disabled.', $disabled);

        $enabled = app('SecurityTxt')->enableCache()
                                     ->clearCache()
                                     ->fetch();

        $this->assertNotEquals($disabled, $enabled);
        $this->assertContains('# Cache is enabled with key "' . $this->cache_key . '".', $enabled);

        for ($x = 0; $x < 10; $x++) {
            $this->assertEquals($enabled, app('SecurityTxt')->fetch());
        }
    }
}
