<?php


namespace Mleko\ValidateCommit\Validator;


class BodySeparationValidator implements \Mleko\ValidateCommit\Validator
{
    const ERROR_BODY_SEPARATION = 'Separate subject from body with a blank line';

    /**
     * @param string $message
     * @return string[]
     */
    public function validate($message)
    {
        $lines = explode("\n", $message);
        if (count($lines) < 2) {
            return [];
        }
        if ('' !== trim($lines[1])) {
            return [self::ERROR_BODY_SEPARATION];
        }
        return [];
    }
}