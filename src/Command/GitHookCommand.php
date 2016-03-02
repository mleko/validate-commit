<?php

namespace Mleko\ValidateCommit\Command;

abstract class GitHookCommand implements Command
{
    const INSTALL_MARKER = "#mleko/validate-commit hook-marker";
    private $gitRoot = null;

    /**
     * @return string
     * @throws \Exception
     */
    protected function findGitHookPath()
    {
        $gitRoot = $this->getGitRoot();
        $gitHookPath = "$gitRoot/.git/hooks/commit-msg";
        return $gitHookPath;
    }

    protected function getGitRoot()
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

    protected function isInstalled()
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
}
