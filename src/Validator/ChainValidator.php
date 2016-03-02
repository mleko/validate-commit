<?php


namespace Mleko\ValidateCommit\Validator;


class ChainValidator implements \Mleko\ValidateCommit\Validator
{

    /**
     * @var \Mleko\ValidateCommit\Validator[]
     */
    private $validators = [];

    /**
     * ChainValidator constructor.
     * @param array $validators
     */
    public function __construct(array $validators)
    {
        $this->validators = $validators;
    }


    /**
     * @param string $message
     * @return string[]
     */
    public function validate($message)
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            $errors = array_merge($errors, $validator->validate($message));
        }
        return $errors;
    }
}