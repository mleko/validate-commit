<?php

namespace Mleko\ValidateCommit\Command;

interface Command
{
    /**
     * @param string[] $args
     * @return int|void
     */
    public function execute($args);
}
