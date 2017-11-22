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

use PHPUnit\Framework\TestResult;

/**
 * RoutesEnabledTest
 */
class RoutesEnabledTest extends DocumentTestCase
{
    public function run(TestResult $result = null)
    {
        if ($result === null) {
            $result = $this->createResult();
        }

        for ($x = 0; $x < LARAVEL_SECURITY_TXT_ITERATIONS; $x++) {
            $result->run($this);
        }

        return $result;
    }

    public function testShow()
    {
        foreach (['GET', 'HEAD'] as $method) {
            $crawler = $this->call($method, '/.well-known/security.txt');
            $crawler->assertStatus(200);

            if ($method == 'GET') {
                $crawler->assertSeeText($this->contact)
                        ->assertSeeText('Encryption: ' . $this->encryption)
                        ->assertSeeText('Acknowledgement: ' . $this->acknowledgement)
                        ->assertSeeText('Disclosure: ' . ucfirst($this->disclosure));
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
}
