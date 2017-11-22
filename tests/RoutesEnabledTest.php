<?php
/**
 * tests/RoutesEnabledTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * RoutesEnabledTest
 */
class RoutesEnabledTest extends TestCase
{
    private $contact;

    public function testShow()
    {
        foreach (['GET', 'HEAD'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(200);

            if ($method == 'GET') {
                $crawler->assertSeeText($this->contact);
            }
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
            $crawler->assertStatus(302);
        }

        foreach (['POST', 'PUT', 'DELETE', 'PATCH'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(405);
        }
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->contact = $this->newRandom('test%s@email.com');

        $app['config']->set('security-txt',
                            [
                                'enabled'  => true,
                                'contacts' => [$this->contact],
                            ]);
    }
}
