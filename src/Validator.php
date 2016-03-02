<?php


namespace Mleko\ValidateCommit;


interface Validator
{
    /**
     * @param string $message
     * @return string[]
     */
    public function validate($message);
}