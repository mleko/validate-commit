<?php

namespace Mleko\ValidateCommit\Command;

class Uninstall extends GitHookCommand
{

    /**
     * @param string[] $args
     * @return int|void
     */
    public function execute($args)
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
}
