<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\Reader;

use HS\Errors\AuthenticationError;
use HS\Reader;
use HS\Tests\TestCommon;
use Stream\Exception\PortValidateStreamException;

class ConstructorTest extends TestCommon
{

    public function testInvalidPortException()
    {
        $portWrong = '-1';

        try {
            $reader = new Reader($this->getHost(), $portWrong);
        } catch (PortValidateStreamException $e) {
            return;
        }

        $this->fail("Reader constructor didn't fall with exception PortValidateStreamException on wrong port set.");
    }

    public function testGoodConnectionToReadPort()
    {
        $portGood = 9998;

        $reader = null;
        try {
            $reader = new Reader($this->getHost(), $portGood);
        } catch (\Exception $e) {
            $this->fail(
                sprintf(
                    "Fall with valid parameters to constructor. Host:%s, port:%s",
                    $this->getHost(),
                    $portGood
                )
            );
        }
        $reader->close();
        $this->assertTrue(true);
    }

    public function testAuthRequestAdded()
    {
        $portGood = 9999;
        $pass = 'testpass';
        try {
            $reader = new Reader($this->getHost(), $portGood, $pass);
            $this->assertEquals(1, $reader->getCountQueriesInQueue(), "Auth request not added on init hs reader.");
            $reader->getResultList();
        } catch (AuthenticationError $e) {
            return true;
        }
        $this->fail("Not fall without auth.");
    }

    public function testAuthRequestNotAdded()
    {
        $portGood = 9999;
        $reader = new Reader($this->getHost(), $portGood);
        $this->assertEquals(0, $reader->getCountQueriesInQueue(), "Auth request added on init hs reader.");
        $reader->getResultList();
        $reader->close();
    }
} 