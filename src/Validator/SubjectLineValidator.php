<?php

namespace Mleko\ValidateCommit\Validator;

class SubjectLineValidator implements \Mleko\ValidateCommit\Validator
{

    const ERROR_TOO_LONG = "Limit the subject line to 50 characters, %d present";
    const ERROR_NOT_CAPITALIZED = "Capitalize subject line";
    const ERROR_EMPTY_SUBJECT = "Subject line cannot be empty";
    const ERROR_PERIOD_END = 'Do not end the subject line with period';

    /**
     * @param string $message
     * @return string[]
     */
    public function validate($message)
    {
        $errors = [];
        $subjectLine = strpos($message, "\n") !== false ? strstr($message, "\n", true) : $message;

        $errors[] = $this->validateLength($subjectLine);
        $errors[] = $this->validateCapital($subjectLine);
        $errors[] = $this->validateIsEmpty($subjectLine);
        $errors[] = $this->validatePeriodEnding($subjectLine);

        return array_values(array_filter($errors));
    }

    private function validateLength($message)
    {
        $length = strlen($message);
        if ($length > 50) {
            return sprintf(static::ERROR_TOO_LONG, $length);
        }
        return null;
    }

    private function validateCapital($message)
    {
        if (ucfirst($message) != $message) {
            return static::ERROR_NOT_CAPITALIZED;
        }
        return null;
    }

    private function validateIsEmpty($message)
    {
        if (trim($message) == '') {
            return self::ERROR_EMPTY_SUBJECT;
        }
        return null;
    }

    private function validatePeriodEnding($message)
    {
        if (substr($message, -1) == '.') {
            return self::ERROR_PERIOD_END;
        }
        return null;
    }
}