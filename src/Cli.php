<?php


namespace Mleko\ValidateCommit;


class Cli
{
    /**
     * @param string[] $argv
     * @return boolean proceed(true) or abort(false) commit
     */
    public function run($argv)
    {
        return $this->validateCommitFile($argv[1]);
    }

    private function validateCommitFile($filePath)
    {
        $message = file_get_contents($filePath);

        $validator = new Validator\ChainValidator(
            [
                new Validator\SubjectLineValidator(),
                new Validator\BodySeparationValidator(),
                new Validator\TextWidthValidator()
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
