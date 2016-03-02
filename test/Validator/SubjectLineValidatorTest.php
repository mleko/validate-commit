<?php

namespace Mleko\ValidateCommit\Test\Validator;

use Mleko\ValidateCommit\Validator\SubjectLineValidator;

class SubjectLineValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SubjectLineValidator
     */
    private $validator;


    /**
     * @param string $message
     * @dataProvider validMessageProvider
     */
    public function testValidMessages($message)
    {
        $this->assertEmpty($this->validator->validate($message));
    }

    public function validMessageProvider()
    {
        return [
            ["Valid commit message"],
            ["Accelerate to 88 miles per hour"],
            ["Open the pod bay doors"],
            ["Remove deprecated methods"]
        ];
    }

    /**
     * @param string[] $expected
     * @param string $message
     * @dataProvider invalidMessageProvider
     */
    public function testInvalidMessages($expected, $message)
    {
        $this->assertContains($expected, $this->validator->validate($message));
    }

    public function invalidMessageProvider()
    {
        $longMessage = 'this is pretty damn long commit subject, it should be more direct, error should be reported';
        return [
            [SubjectLineValidator::ERROR_NOT_CAPITALIZED, 'non capitalized subject'],
            [SubjectLineValidator::ERROR_PERIOD_END, 'Open the pod bay doors.'],
            [SubjectLineValidator::ERROR_EMPTY_SUBJECT, ''],
            [sprintf(SubjectLineValidator::ERROR_TOO_LONG, strlen($longMessage)), $longMessage]
        ];
    }

    protected function setUp()
    {
        $this->validator = new SubjectLineValidator();
    }


}
