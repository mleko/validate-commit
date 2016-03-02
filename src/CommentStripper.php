<?php


namespace Mleko\ValidateCommit;


class CommentStripper
{
    /**
     * @param string $message
     * @return string
     */
    public function stripComments($message)
    {
        $lines = explode("\n", $message);
        return implode(
            "\n",
            array_filter(
                $lines,
                function ($s) {
                    return strlen($s) == 0 || $s[0] != '#';
                }
            )
        );
    }
}
