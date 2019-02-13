<?php

namespace Tests\Browser;

use DigitalBibleSociety\DBPDusk\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class AnnotationTest extends DuskTestCase
{

    /** @test */
    public function userCanSeeHighlightList()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/bible/engesv/mat/1');

            $this->loginUser($browser);

            $browser->click('#notebook-button')
                    ->waitFor('#note-list-button')
                    ->click('#highlights-list-button')
                    ->waitFor('.highlight-item');
        });

    }

    /** @test */
    public function userCanCreateAHighlight()
    {
        //$this->markTestIncomplete('Login times out');
        $this->browse(function (Browser $browser) {
            $browser->visit('bible/engesv/mat/1');

            $this->loginUser($browser);

            // Select Verse to highlight
            $browser->click('.verse6');

            // Remove any existing Highlights
            $browser->click('#add-highlight')->waitFor('.color-group', 2)->with('.color-group', function ($table) {
                $table->assertSee('None')->click('.none');
            });

            // Add a Yellow Highlight
            $browser->with('.color-group', function ($table) {
                $table->assertSee('Yellow')->click('.yellow');
            });

            // Open up the Highlights portion of the Notebook
            $browser->click('#notebook-button')->waitFor('#highlights-list-button', 2)->click('#highlights-list-button');


        });
    }

    /** @test */
    public function userCanCreateANote()
    {
        $this->markTestIncomplete('Needs to have custom IDs on the context Menu');
        $this->browse(function (Browser $browser) {
            $browser->visit('https://dev-listen.dbp4.org/bible/engesv/mat/1');
            $this->loginUser($browser);

            $browser->click('.verse6');
            $browser->script('$(".context-menu .button[title=\'Add a note\']").click();');

            // Remove any existing Highlights
            $browser->script('$(".highlight-colors .color-group:nth-child(1)).click();');

            // Add a Yellow Highlight
            $browser->script('$(".highlight-colors .color-group:nth-child(2)).click();');

            // Open up the notebook
            $browser->script('$(".footer-content span[title=\'NoteBook\']").click();');

            // See the new Highlight
            $browser->script('$(".notes .top-bar .nav-button:nth-child(2)").click();');

            // Search Highlights for newly created one And assert See
        });
    }


}
