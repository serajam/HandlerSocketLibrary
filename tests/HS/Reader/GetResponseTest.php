<?php
/**
 * @author KonstantinKuklin <konstantin.kuklin@gmail.com>
 */

namespace HS\Tests\HSReader;

use HS\Tests\TestCommon;

class GetResponseTest extends TestCommon
{

    public function testSelectExistedValue()
    {
        $hsReader = $this->getReader();

        $indexId = $hsReader->getIndexId(
            $this->getDatabase(),
            'hs_read',
            'PRIMARY',
            array('key', 'date', 'float', 'varchar', 'text', 'set', 'null', 'union')
        );
        $selectRequest = $hsReader->select($indexId, '=', array(42));

        $expectedResult = array(
            array(
                'key' => '42',
                'date' => '2010-10-29',
                'float' => '3.14159',
                'varchar' => 'variable length',
                'text' => "some\r\nbig\r\ntext",
                'set' => 'a,c',
                'union' => 'b',
                'null' => null
            )
        );

        $this->checkAssertionLastResponseData($hsReader, 'first test method', $expectedResult);
    }

    public function testSelectWithZeroValue()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), 'hs_read', 'PRIMARY', array('float'));
        $hsReader->select($id, '=', array(100));

        $expectedValue = array(array('float' => 0));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);
    }

    public function testSelectWithSpecialChars()
    {
        $hsReader = $this->getReader();
        $id = $hsReader->getIndexId($this->getDatabase(), 'hs_read', 'PRIMARY', array('text'));
        $hsReader->select($id, '=', array(10001));

        $expectedValue = array(array("text" => "\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F"));
        $this->checkAssertionLastResponseData($hsReader, "test", $expectedValue);;
    }
} 