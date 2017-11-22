<?php
/**
 * tests/RoutesTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * RoutesTest
 */
class RoutesTest extends TestCase
{
    public function testShow()
    {
        foreach (['GET', 'HEAD'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(404);
        }

        foreach (['POST', 'PUT', 'DELETE', 'PATCH'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(405);
        }
    }

    public function testRedirect()
    {
        foreach (['GET', 'HEAD'] as $method) {
            $crawler = $this->call($method, '/security.txt');
            $crawler->assertStatus(404);
        }

        foreach (['POST', 'PUT', 'DELETE', 'PATCH'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(405);
        }
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('security-txt',
                            [
                                'enabled' => false,
                            ]);
    }

}
