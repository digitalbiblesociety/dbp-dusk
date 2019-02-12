<?php

namespace Tests\Browser;

use DigitalBibleSociety\DBPDusk\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AuthenticationTest extends DuskTestCase
{

    /**
     *
     * @throws \Throwable
     * @test
     */
    public function userCanLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');

            $browser->click('#profile-button')
                ->waitFor('#email')
                ->type('#email', 'jsnahill@gmail.com')
                ->type('#password', 'conquest')
                ->press('Sign In')
                ->waitFor('.logout-button', 6)
                ->assertSeeIn('.logout-button','Logout');
        });
    }

}
