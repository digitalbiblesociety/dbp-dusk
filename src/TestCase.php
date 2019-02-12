<?php

namespace DigitalBibleSociety\DBPDusk;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\SupportsChrome;
use Laravel\Dusk\Concerns\ProvidesBrowser;
use PHPUnit\Framework\TestCase as BaseTestCase;
use DigitalBibleSociety\DBPDusk\Concerns\StartsChrome;
use DigitalBibleSociety\DBPDusk\Exceptions\NotADirectory;
use DigitalBibleSociety\DBPDusk\Exceptions\CannotCreateDirectory;

class TestCase extends BaseTestCase
{
    use ProvidesBrowser,
        SupportsChrome,
        StartsChrome;

    public static $directoriesCreated = false;

    /**
     * Register the base URL with Dusk.
     *
     * @return void
     * @throws \Exception
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\CannotCreateDirectory
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\NotADirectory
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setUpTheBrowserEnvironment();
    }

    /**
     * Setup the browser environment.
     *
     * @return void
     * @throws \Exception
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\CannotCreateDirectory
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\NotADirectory
     */
    protected function setUpTheBrowserEnvironment()
    {
        $this->createDirectories($this->browserTestsPath(), ['screenshots', 'console']);

        Browser::$baseUrl = $this->baseUrl();
        Browser::$storeScreenshotsAt = $this->browserTestsPath().'/screenshots';
        Browser::$storeConsoleLogAt = $this->browserTestsPath().'/console';
    }

    /**
     * Create directories.
     *
     * @param $path
     * @param $directories
     *
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\NotADirectory
     * @throws \DigitalBibleSociety\DBPDusk\Exceptions\CannotCreateDirectory
     */
    protected function createDirectories($path, $directories)
    {
        if (static::$directoriesCreated) {
            return;
        }

        if (! is_dir($path)) {
            throw new NotADirectory($path);
        }

        foreach ($directories as $dir) {
            @mkdir($path.'/'.$dir);
            if (! is_dir($path.'/'.$dir)) {
                throw new CannotCreateDirectory("{$dir}' at '{$path}");
            }
        }
    }

    /**
     * Determine the application's base URL.
     *
     * @var string
     * @return string
     */
    protected function baseUrl()
    {
        return 'http://localhost';
    }

    /**
     * Determine the path for Browser Tests.
     *
     * @return string
     * @throws \Exception
     */
    protected function browserTestsPath()
    {
        return \dirname(__DIR__).'/tests/Browser';
    }
}
