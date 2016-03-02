<?php


namespace Mleko\ValidateCommit\Test\Validator;


class ValidMessageProvider
{
    private static $cache = null;

    public static function provide()
    {
        if (!static::$cache) {
            static::$cache = static::loadCache();
        }
        return static::$cache;
    }

    private static function loadCache()
    {
        return [
            ["Valid commit message"],
            ["Accelerate to 88 miles per hour"],
            ["Open the pod bay doors"],
            ["Remove deprecated methods"],
            [file_get_contents(__DIR__ . '/samples/valid.message.1.txt')]
        ];
    }
}