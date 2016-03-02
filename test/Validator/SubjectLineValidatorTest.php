<?php

namespace Mleko\ValidateCommit\Test\Validator;

use Mleko\ValidateCommit\Validator\SubjectLineValidator;

class SubjectLineValidatorTest extends AbstractValidatorTest
{
    /**
     * @return \Mleko\ValidateCommit\Validator
     */
    function getValidator()
    {
        return new SubjectLineValidator();
    }

    public function invalidMessageProvider()
    {
        $longMessage = file_get_contents(__DIR__ . '/samples/lorem.ipsum.txt');
        return [
            [SubjectLineValidator::ERROR_NOT_CAPITALIZED, 'non capitalized subject'],
            [SubjectLineValidator::ERROR_PERIOD_END, 'Open the pod bay doors.'],
            [SubjectLineValidator::ERROR_EMPTY_SUBJECT, ''],
            [sprintf(SubjectLineValidator::ERROR_TOO_LONG, 89), $longMessage]
        ];
    }
}
