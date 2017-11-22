<?php
/**
 * tests/ConfigEnvTest.php
 *
 * @package     laravel-security-txt
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.4.0
 */

namespace AustinHeap\Security\Txt\Tests;

use PHPUnit\Framework\TestResult;

/**
 * ConfigEnvTest
 */
class ConfigEnvTest extends DocumentTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('security-txt', []);

        $this->assertEmpty($app['config']->get('security-txt'));

        putenv('SECURITY_TXT_ENABLED=true');
        putenv('SECURITY_TXT_DEBUG=' . ($this->debug ? 'true' : 'false'));
        putenv('SECURITY_TXT_CACHE=' . ($this->cache ? 'true' : 'false'));
        putenv('SECURITY_TXT_CACHE_TIME=' . $this->cache_time);
        putenv('SECURITY_TXT_CACHE_KEY=' . $this->cache_key);
        putenv('SECURITY_TXT_COMMENTS=' . ($this->comments ? 'true' : 'false'));
        putenv('SECURITY_TXT_CONTACT=' . $this->contact);
        putenv('SECURITY_TXT_ENCRYPTION=' . $this->encryption);
        putenv('SECURITY_TXT_DISCLOSURE=' . $this->disclosure);
        putenv('SECURITY_TXT_ACKNOWLEDGEMENT=' . $this->acknowledgement);
    }

    protected function tearDown()
    {
        $keys = [
            'SECURITY_TXT_ENABLED',
            'SECURITY_TXT_DEBUG',
            'SECURITY_TXT_CACHE',
            'SECURITY_TXT_CACHE_TIME',
            'SECURITY_TXT_CACHE_KEY',
            'SECURITY_TXT_COMMENTS',
            'SECURITY_TXT_CONTACT',
            'SECURITY_TXT_ENCRYPTION',
            'SECURITY_TXT_DISCLOSURE',
            'SECURITY_TXT_ACKNOWLEDGEMENT',
        ];

        foreach ($keys as $key) {
            putenv($key);
            $this->assertFalse(getenv($key));
        }

        parent::tearDown();
    }

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

    public function testConfig()
    {
        $config = require __DIR__ . '/../src/config/security-txt.php';

        app('config')->set('security-txt', $config);

        $this->assertArraySubset(
            [
                'enabled'         => true,
                'debug'           => $this->debug,
                'cache'           => $this->cache,
                'cache-time'      => $this->cache_time,
                'cache-key'       => $this->cache_key,
                'comments'        => $this->comments,
                'contacts'        => [$this->contact],
                'encryption'      => $this->encryption,
                'disclosure'      => $this->disclosure,
                'acknowledgement' => $this->acknowledgement,
            ],
            app('config')->get('security-txt'), true);
    }

    public function testFetch()
    {
        $config = require __DIR__ . '/../src/config/security-txt.php';

        app('config')->set('security-txt', $config);

        $document = securitytxt()->buildWriter()->fetch();

        $this->assertContains('Contact: ' . $this->contact, $document);
        $this->assertContains('Encryption: ' . $this->encryption, $document);
        $this->assertContains('Acknowledgement: ' . $this->acknowledgement, $document);
        $this->assertContains('Disclosure: ' . ucfirst($this->disclosure), $document);
    }

}
