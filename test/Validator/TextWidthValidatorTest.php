<?php


namespace Mleko\ValidateCommit\Test\Validator;


use Mleko\ValidateCommit\Validator\TextWidthValidator;

class TextWidthValidatorTest extends AbstractValidatorTest
{
    /**
     * @return \Mleko\ValidateCommit\Validator
     */
    function getValidator()
    {
        return new TextWidthValidator();
    }

    /**
     * @return array
     */
    function invalidMessageProvider()
    {
        return [
            [sprintf(TextWidthValidator::ERROR_BODY_TOO_WIDE, 100), file_get_contents(__DIR__ . '/samples/lorem.ipsum.txt')]
        ];
    }

}
