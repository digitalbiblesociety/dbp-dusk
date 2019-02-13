<?php

namespace DigitalBibleSociety\DBPDusk\Tests;

use DigitalBibleSociety\DBPDusk\Exceptions\ErrorHandler;
use DigitalBibleSociety\DBPDusk\TestCase;

class DuskTestCase extends TestCase
{

    use ErrorHandler;

    /**
     * Over-ride parent browse
     *
     * @param  \Closure  $callback
     * @return \Laravel\Dusk\Browser|void
     * @throws \Exception
     * @throws \Throwable
     */
    public function browse($callback)
    {
        $browsers = $this->createBrowsersFor($callback);
        try {
            $callback(...$browsers->all());
        } catch (Exception $e) {
            $this->captureFailuresFor($browsers);
            $this->reportToNewRelic($e);
            throw $e;
        } finally {
            $this->storeConsoleLogsFor($browsers);
            static::$browsers = $this->closeAllButPrimary($browsers);
        }
    }

    protected function baseUrl():string
    {
        return 'https://dev-listen.dbp4.org';
    }

    protected function loginUser($browser):void
    {
        $browser->click('#profile-button')
                ->waitFor('#email')
                ->type('#email', 'jsnahill@gmail.com')
                ->type('#password', 'conquest')
                ->press('Sign In')
                ->waitFor('.logout-button', 6)
                ->assertSeeIn('.logout-button','Logout');
    }
}