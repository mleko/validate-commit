<?php


namespace Mleko\ValidateCommit\Validator;


class TextWidthValidator implements \Mleko\ValidateCommit\Validator
{
    const ERROR_BODY_TOO_WIDE = 'Wrap the body at 72 characters, current max: %d';

    /**
     * @param string $message
     * @return string[]
     */
    public function validate($message)
    {
        $lines = explode("\n", $message);
        $max = max(array_map('strlen', $lines));
        if ($max > 72) {
            return [sprintf(self::ERROR_BODY_TOO_WIDE, $max)];
        }
        return [];
    }
}