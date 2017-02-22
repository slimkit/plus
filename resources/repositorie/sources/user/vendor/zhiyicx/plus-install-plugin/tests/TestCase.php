<?php

namespace Zhiyi\Component\Installer\PlusInstallPlugin\Test;

use Composer\Semver\VersionParser;
use Composer\Util\Filesystem;
use Composer\Util\Silencer;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    private static $parser;

    protected static function getVersionParser()
    {
        if (!self::$parser) {
            self::$parser = new VersionParser();
        }

        return self::$parser;
    }

    public static function getUniqueTmpDirectory()
    {
        $attempts = 5;
        $root = sys_get_temp_dir();

        do {
            $unique = $root.DIRECTORY_SEPARATOR.uniqid('plus-test-'.rand(1000, 9000));

            if (!file_exists($unique) && Silencer::call('mkdir', $unique, 0777)) {
                return realpath($unique);
            }
        } while (--$attempts);

        throw new \RuntimeException('Failed to create a unique temporary directory.');
    }

    protected static function ensureDirectoryExistsAndClear($directory)
    {
        $fs = new Filesystem();

        if (is_dir($directory)) {
            $fs->removeDirectory($directory);
        }

        mkdir($directory, 0777, true);
    }
}
