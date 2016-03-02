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
        try {
            array_shift($argv); //ignored so far
            $code = $this->exec($argv);
            exit((int)$code);
        } catch (\Exception $e) {
            $this->printHelp();
            echo "Exception: {$e->getMessage()}\n";
            exit(1);
        }
    }

    public function exec($args)
    {
        $commandName = strtolower(array_shift($args));

        switch ($commandName) {
            case 'validate':
                $command = new Command\Validate();
                break;
            case 'install':
                $command = new Command\Install();
                break;
            case 'uninstall':
                $command = new Command\Uninstall();
                break;
            case 'help':
                $this->printHelp();
                return 0;
            default:
                throw new \Exception("Unknown command");
        }
        return $command->execute($args);
    }

    private function printHelp()
    {
        echo "validate-commit\n";
        echo "Usage: validate-commit validate MSG_FILE\n";
        echo "Usage: validate-commit install\n";
        echo "Usage: validate-commit uninstall\n";
    }
}
