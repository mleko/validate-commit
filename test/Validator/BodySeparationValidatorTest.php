<?php


namespace Mleko\ValidateCommit\Test\Validator;


use Mleko\ValidateCommit\Validator\BodySeparationValidator;

class BodySeparationValidatorTest extends AbstractValidatorTest
{
    /**
     * @return \Mleko\ValidateCommit\Validator
     */
    public function getValidator()
    {
        return new BodySeparationValidator();
    }

    public function invalidMessageProvider()
    {
        return [
            [BodySeparationValidator::ERROR_BODY_SEPARATION, file_get_contents(__DIR__ . '/samples/lorem.ipsum.txt')]
        ];
    }
}
