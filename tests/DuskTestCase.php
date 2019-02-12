<?php

namespace DigitalBibleSociety\DBPDusk\Tests;

use DigitalBibleSociety\DBPDusk\TestCase;

class DuskTestCase extends TestCase
{
    protected function baseUrl()
    {
        return 'https://dev-listen.dbp4.org';
    }

    protected function browserTestsPath()
    {
        return parent::browserTestsPath();
    }

    protected function loginUser($browser)
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