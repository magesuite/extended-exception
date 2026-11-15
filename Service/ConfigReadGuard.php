<?php

namespace MageSuite\ExtendedException\Service;

class ConfigReadGuard
{
    private static bool $reading = false;

    public static function run(callable $read)
    {
        if (self::$reading) {
            return null;
        }

        self::$reading = true;

        try {
            return $read();
        } finally {
            self::$reading = false;
        }
    }
}
