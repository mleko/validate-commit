<?php

namespace Mleko\ValidateCommit\Command;

class Validate implements Command
{

    /**
     * @param string[] $args
     * @return int|void
     */
    public function execute($args)
    {
        $filename = array_shift($args);
        if ($filename) {
            return $this->validateCommitFile($filename) ? 0 : 1;
        }
        echo "Missing filename";
        return 1;
    }

    private function validateCommitFile($filePath)
    {
        $message = file_get_contents($filePath);

        $validator = new \Mleko\ValidateCommit\Validator\ChainValidator(
            [
                new \Mleko\ValidateCommit\Validator\SubjectLineValidator(),
                new \Mleko\ValidateCommit\Validator\BodySeparationValidator(),
                new \Mleko\ValidateCommit\Validator\TextWidthValidator()
            ]
        );
        $errors = $validator->validate($message);

        if ($errors) {
            return $this->handleErrors($message, $errors);
        }
        return true;
    }

    private function handleErrors($message, $errors)
    {
        echo $message;
        echo "\nErrors:\n";
        foreach ($errors as $k => $error) {
            printf("#%d: %s\n", $k + 1, $error);
        }
        echo "\nForce commit? [y/n]:";
        $response = trim(fgets(STDIN));
        return strtolower($response) === 'y';
    }
}
