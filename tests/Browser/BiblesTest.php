<?php

namespace Tests\Browser;

use DigitalBibleSociety\DBPDusk\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class BiblesTest extends DuskTestCase
{

    /** @test */
    public function ensureBibleHasText()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertDontSee('Text is not currently available for this version.');

            $browser->visit('bible/ENGESV/JHN/1')
                    ->assertDontSee('Audio Only');
        });
    }

    /** @test */
    public function ensureCopyrightedTextDoesNotAppear() {
        $this->browse(function (Browser $browser) {
            $browser->visit('bible/GBMWIN/JHN/1')
                    ->assertSee('Audio Only');
        });
    }

    /** @test */
    public function ensureLanguageSelectorPopulates() {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->waitFor('.version')
                    ->click('.version')
                    ->waitFor('#language-tab')
                    ->click('#language-tab')
                    ->assertSeeIn('.language-name-list','English');
        });
    }

    /** @test */
    public function nonEnglishLanguageMustNotShowAnEnglishBible()
    {
        $this->markTestIncomplete('Awaiting Unique IDs on language List');

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->waitFor('.version', 2)
                    ->click('.version')
                    ->waitFor('#language-tab', 2)
                    ->click('#language-tab');
            // $browser->script('$(".language-name-list div[title=\'Spanish\']").click();');
            $browser->waitUntilMissing('.loading-spinner', 5)
                    ->assertDontSeeIn('.version-name-list','English');
        });
    }

}