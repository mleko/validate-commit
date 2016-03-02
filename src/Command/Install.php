<?php

namespace Mleko\ValidateCommit\Command;

class Install extends GitHookCommand
{

    public function execute($args)
    {
        if (false !== $this->isInstalled()) {
            echo "Hook already installed\n";
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
        $cmd = realpath(__DIR__ . '/../../hook') . " \"\$1\"; ";
        file_put_contents($gitHookPath, "$hookContents\n$cmd" . self::INSTALL_MARKER . "\n");
        if (!$exists) {
            chmod($gitHookPath, 0755);
        }
        echo "Hook installed\n";
    }
}
