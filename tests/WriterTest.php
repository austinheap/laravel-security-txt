<?php
/**
 * tests/HelperTestCase * @package     laravel-security-txt
 *
 * @link        https://github.com/austinheap/laravel-security-txt
 * @author      Austin Heap <me@austinheap.com>
 * @version     v0.3.2
 */

namespace AustinHeap\Security\Txt\Tests;

use AustinHeap\Security\Txt\SecurityTxtHelper;
use AustinHeap\Security\Txt\Writer;
use Exception;

/**
 * HelperTestCase
 */
class SecurityTextHelperTest extends HelperTestCase
{
    public function testWriterConstructed()
    {
        $this->assertEquals(true, app('SecurityTxt')->hasWriter());
    }

    public function testSetWriter()
    {
        $writer = new Writer();

        app('SecurityTxt')->setWriter($writer);

        $this->assertEquals($writer, app('SecurityTxt')->getWriter());
        $this->assertAttributeEquals($writer, 'writer', app('SecurityTxt'));
    }

    public function testBuildWriterValid()
    {
        $this->assertEquals(app('SecurityTxt')->buildWriter(), app('SecurityTxt'));
    }

    public function testBuildWriterInvalidValidator()
    {
        $helper                     = new SecurityTxtHelper();
        $keys                       = SecurityTxtHelper::buildWriterDefaultKeys();
        $keys['security-txt.cache'] = [
            'validator' => $this->newRandom('is_bool%s'), // invalid
            'setter'    => 'setCache',
        ];
        $log_entries                = $helper->setWriter(null)
                                             ->clearLogEntries()
                                             ->buildWriter($keys)
                                             ->getLogEntries();

        $this->assertEquals('"AustinHeap\Security\Txt\SecurityTxtHelper" cannot find "validator" function named "' . $keys['security-txt.cache']['validator'] . '".',
                            $log_entries[0]['text']);
    }

    public function testBuildWriterInvalidValidation()
    {
        $helper                     = new SecurityTxtHelper();
        $keys                       = SecurityTxtHelper::buildWriterDefaultKeys();
        $keys['security-txt.cache'] = [
            'validator' => 'is_array', // bool will fail
            'setter'    => 'setCache',
            'self'      => true,
        ];
        $log_entries                = $helper->setWriter(null)
                                             ->clearLogEntries()
                                             ->buildWriter($keys)
                                             ->getLogEntries();

        $this->assertEquals('"AustinHeap\Security\Txt\SecurityTxtHelper" failed the "validator" function named "' . $keys['security-txt.cache']['validator'] . '".',
                            $log_entries[0]['text']);
    }

    public function testBuildWriterInvalidSetterSelf()
    {
        $helper                     = new SecurityTxtHelper();
        $keys                       = SecurityTxtHelper::buildWriterDefaultKeys();
        $keys['security-txt.cache'] = [
            'validator' => 'is_bool',
            'setter'    => $this->newRandom('setCache%s'), // invalid
            'self'      => true,
        ];
        $log_entries                = $helper->setWriter(null)
                                             ->clearLogEntries()
                                             ->buildWriter($keys)
                                             ->getLogEntries();

        $this->assertEquals('"AustinHeap\Security\Txt\SecurityTxtHelper" cannot find mapping "setter" method on object "AustinHeap\Security\Txt\SecurityTxtHelper" named "' . $keys['security-txt.cache']['setter'] . '".',
                            $log_entries[0]['text']);
    }

    public function testBuildWriterInvalidSetter()
    {
        $helper                     = new SecurityTxtHelper();
        $keys                       = SecurityTxtHelper::buildWriterDefaultKeys();
        $keys['security-txt.cache'] = [
            'validator' => 'is_bool',
            'setter'    => $this->newRandom('setCache%s'), // invalid
            'self'      => false,
        ];
        $log_entries                = $helper->setWriter(null)
                                             ->clearLogEntries()
                                             ->buildWriter($keys)
                                             ->getLogEntries();

        $this->assertEquals('"AustinHeap\Security\Txt\SecurityTxtHelper" cannot find mapping "setter" method on object "AustinHeap\Security\Txt\Writer" named "' . $keys['security-txt.cache']['setter'] . '".',
                            $log_entries[0]['text']);

        //        // invalid: self
        //        $helper                     = new SecurityTxtHelper();
        //        $keys                       = SecurityTxtHelper::buildWriterDefaultKeys();
        //        $keys['security-txt.cache'] = [
        //            'validator' => $this->newRandom('is_bool%s'),
        //            'setter'    => 'setCache',
        //            'self'      => true, // invalid
        //        ];
        //        $log_entries                = $helper->setWriter(null)
        //                                             ->clearLogEntries()
        //                                             ->buildWriter($keys)
        //                                             ->getLogEntries();
        //
        //        $this->assertEquals('"AustinHeap\Security\Txt\SecurityTxtHelper" cannot find mapping "validator" method named "' . $keys['security-txt.cache']['validator'] . '".', $log_entries[0]['text']);
    }

    public function testSetWriterNull()
    {
        app('SecurityTxt')->setWriter(null);

        $this->assertAttributeEmpty('writer', app('SecurityTxt'));
    }

    public function testHasWriter()
    {
        app('SecurityTxt')->setWriter(null);

        $this->assertEquals(false, app('SecurityTxt')->hasWriter());
        $this->assertAttributeEmpty('writer', app('SecurityTxt'));

        app('SecurityTxt')->buildWriter();

        $this->assertAttributeNotEmpty('writer', app('SecurityTxt'));
        $this->assertEquals(true, app('SecurityTxt')->hasWriter());
        $this->assertAttributeEquals(app('SecurityTxt')->getWriter(), 'writer', app('SecurityTxt'));
    }

    public function testGetWriterValid()
    {
        $this->assertInstanceOf(
            Writer::class,
            app('SecurityTxt')->setWriter(null)
                              ->buildWriter()
                              ->getWriter()
        );
    }

    public function testGetWriterInvalid()
    {
        app('SecurityTxt')->buildWriter()
                          ->setWriter(null);

        $this->expectException(Exception::class);

        app('SecurityTxt')->getWriter();
    }
}
