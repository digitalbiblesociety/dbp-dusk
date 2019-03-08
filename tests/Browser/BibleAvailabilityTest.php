<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BibleAvailabilityTest extends DuskTestCase
{

	public $skip_languages = [];

	 /** @test */
    public function country_to_text_test()
    {
		//	TODO: Strip out empty spaces from csv
		$this->browse(function (Browser $browser) {
			$bibles = $this->read_csv("tests/Browser/bible_data.csv");
			
			foreach ($bibles as $bible) {
			
				if ( in_array($bible["Language"], $this->skip_languages) ) { continue; }	// skip desired languages
				
// 				echo "running ".$bible["Dam_ID"]."...\n";
				
				$browser->visit("/")
						->waitUntilMissing('.loading-spinner') // wait for default bible to populate
						->click("#version-dropdown-button")
						->waitFor("#country-tab") // wait for javscript to render menu
						->click("#country-tab")
						->type('#version-search', $bible['Country'])
						->waitFor('.country-name[title="'.$bible['Country'].'"]')
	                    ->click('.country-name[title="'.$bible['Country'].'"]')
	                    ->type('#version-search', $bible["Language"])
	                    ->waitFor(".language-name[title='".$bible["Language"]."']")
	                    ->click(".language-name[title='".$bible["Language"]."']")
	                    ->waitFor(".accordion-title-style [title='".$bible["Version"]."']")
	                    ->click(".accordion-title-style [title='".$bible["Version"]."']");
	                    
	            $drama = $bible['Drama'];
	            if ($drama === "drama") {
		            $browser->clickLink('Dramatized Version');
	            
	            } else if ($drama === "non_drama") {
		            $browser->clickLink('Non-Dramatized Version');
	            }
	            
                $browser->waitForReload();
                
//                 file_put_contents('tests/Browser/source.html', $browser->driver->getPageSource());
                
                if ($bible['Book'] !== "none" && $bible['Chapter'] !== "none") {
                	$browser->click("#chapter-dropdown-button")
                			->waitFor("#".$bible['Book']." > h4")
                			->click("#".$bible['Book']." > h4")
                			->click("#".$bible['Book']." .chapter-container a:nth-of-type(".$bible['Chapter'].")")
                			->waitUntilMissing('.loading-spinner');
            	}
	                    
                // check if LUMO film is present for bibles that should have it
                if ($bible['Lumo_Film'] === "Yes") {
                	$browser->assertPresent('.video-player-container.active');    
                }
	            
	            $format = $bible['Format'];
	            
	            if ($format === 'audio_only') {
		        	$browser->assertSee('Audio Only');    
	            
	            } else {
		            
		            if ($format === 'text_only') { 	
			            $browser->assertPresent('.audioplayer-handle.closed');	// check to make sure audio box is closed
		            }

					if ($bible['Text'] !== "none") {
						$browser->assertSee($bible["Text"]);	// check if text exists on page
					}		            
		       	}
			}
		});
    }
    
    private function read_csv($file) {				
		
		// this prevents problems with different line endings
		ini_set('auto_detect_line_endings', true);
		
		
		// get file and open it into a variable 
		$file = fopen($file, 'r') or die('Unable to open file!');
		
		
		// set array to return data in
		$returnVal = array();
		
		// set header array variable
		$headers = null;
		$index = 0;
		// loop through data
		while ( ($row = fgetcsv($file)) !== FALSE) {
			
			if ( $index === 0 ) { 
				$headers = $row; 
			
			} else {
				if ( !$headers ) { continue; } // double check headers are there
				
				$new_array = [];
				foreach ($headers as $i => $header) {
					$new_array[$header] = $row[$i];
				}
				$returnVal[] = $new_array;
			}
			
			$index++;
		}
		
		// close csv file 
		fclose($file);
		return $returnVal;
	}
}
