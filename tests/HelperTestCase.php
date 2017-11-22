<?php
/**
 * tests/HelperTestCase * @package     laravel-security-txt
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */

namespace AustinHeap\Security\Txt\Tests;

/**
 * HelperTestCase
 */
class HelperTestCase extends TestCase
{
    protected $debug = true;
    protected $cache;
    protected $cache_time;
    protected $cache_key;
    protected $comments;
    protected $contact;
    protected $encryption;
    protected $disclosure;
    protected $acknowledgement;

    public function testFetch()
    {
        $data = app('SecurityTxt')->fetch();

        $this->assertContains('Contact: ' . $this->contact, $data);
        $this->assertContains('Encryption: ' . $this->encryption, $data);
        $this->assertContains('Disclosure: ' . ucfirst($this->disclosure), $data);
        $this->assertContains('Acknowledgement: ' . $this->acknowledgement, $data);
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->cache           = $this->newRandom([true, false]);
        $this->cache_time      = $this->newRandom([1, 2, 3, 4, 5]);
        $this->cache_key       = $this->newRandom('unit-test-cache-key:%s');
        $this->comments        = $this->newRandom([true, false]);
        $this->contact         = $this->newRandom('test%s@email.com');
        $this->encryption      = $this->newRandom('https://www.testdomain%s.com/.well-known/gpg.txt');
        $this->disclosure      = rand(1, 100) > 50 ? 'full' : 'partial';
        $this->acknowledgement = $this->newRandom('https://www.testdomain%s.com/some/uri/');

        $app['config']->set('security-txt',
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
                            ]);
    }

}
