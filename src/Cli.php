<?php


namespace Mleko\ValidateCommit;


class Cli
{
    const INSTALL_MARKER = "#mleko/validate-commit hook-marker";
    private $gitRoot = null;

    /**
     * @param string[] $argv
     * @return boolean proceed(true) or abort(false) commit
     */
    public function run($argv)
    {
        try {
            array_shift($argv); //ignored so far
            $code = $this->exec($argv);
            exit((int)$code);
        } catch (\Exception $e) {
            echo "Exception: {$e->getMessage()}\n";
            echo $e->getTraceAsString();
            exit(1);
        }
    }

    public function exec($args)
    {
        $command = strtolower(array_shift($args));

        switch ($command) {
            case 'validate':
                $filename = array_shift($args);
                if ($filename) {
                    exit($this->validateCommitFile($filename) ? 0 : 1);
                }
                echo "Missing filename";
                break;
            case 'install':
                return $this->install();
            case 'uninstall':
                return $this->uninstall();
            case 'help':
            default:
                $this->printHelp();
        }
        return 0;
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

    private function printHelp()
    {
        echo "validate-commit\n";
        echo "Usage: validate-commit validate MSG_FILE\n";
        echo "Usage: validate-commit install\n";
        echo "Usage: validate-commit uninstall\n";
    }

    private function getGitRoot()
    {
        if (null === $this->gitRoot) {
            $root = exec('git rev-parse --show-toplevel', $output, $status);
            if (0 != $status) {
                throw new \Exception("Git root not found");
            }
            $this->gitRoot = $root;
        }
        return $this->gitRoot;
    }

    private function install()
    {
        if (false !== $this->isInstalled()) {
            echo "Already installed\n";
            return;
        }

        $gitHookPath = $this->findGitHookPath();

        $exists = file_exists($gitHookPath);
        $hookContents = $exists ? file_get_contents($gitHookPath) : "#!/bin/sh\n";

        // if file ends with empty line pop it
        $lines = explode("\n", $hookContents);
        if ("" == array_pop($lines)) {
            $hookContents = implode("\n", $lines);
        }
        $cmd = realpath(__DIR__ . '/../hook') . " \"\$1\"; ";
        file_put_contents($gitHookPath, "$hookContents\n$cmd" . self::INSTALL_MARKER . "\n");
        if (!$exists) {
            chmod($gitHookPath, 0755);
        }
        echo "Hook installed\n";
    }

    private function isInstalled()
    {
        $gitHookPath = $this->findGitHookPath();
        if (!file_exists($gitHookPath)) {
            return false;
        }
        $hookContents = file_get_contents($gitHookPath);
        $lines = explode("\n", $hookContents);
        foreach ($lines as $k => $line) {
            $pos = strpos($line, self::INSTALL_MARKER);
            if (false !== $pos) {
                return $k;
            }
        }
        return false;
    }

    private function uninstall()
    {
        $position = $this->isInstalled();
        if (false == $position) {
            echo "Hook not installed\n";
            return 0;
        }

        $gitHookPath = $this->findGitHookPath();
        $hookContents = file_get_contents($gitHookPath);
        $lines = explode("\n", $hookContents);
        unset($lines[$position]);
        file_put_contents($gitHookPath, implode("\n", $lines));
        echo "Uninstalled\n";
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function findGitHookPath()
    {
        $gitRoot = $this->getGitRoot();
        $gitHookPath = "$gitRoot/.git/hooks/commit-msg";
        return $gitHookPath;
    }
}
