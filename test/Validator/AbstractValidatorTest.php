<?php


namespace Mleko\ValidateCommit\Test\Validator;


abstract class AbstractValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \Mleko\ValidateCommit\Validator
     */
    abstract function getValidator();

    /**
     * @return array
     */
    abstract function invalidMessageProvider();

    /**
     * @param string $message
     * @dataProvider validMessageProvider
     */
    public function testValidMessage($message)
    {
        $this->assertEmpty($this->getValidator()->validate($message));
    }

    public function validMessageProvider()
    {
        return ValidMessageProvider::provide();
    }

    /**
     * @param string[] $expected
     * @param string $message
     * @dataProvider invalidMessageProvider
     */
    public function testInvalidMessages($expected, $message)
    {
        $this->assertContains($expected, $this->getValidator()->validate($message));
    }
}