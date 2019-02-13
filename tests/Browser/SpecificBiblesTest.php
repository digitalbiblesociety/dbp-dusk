<?php

namespace Tests\Browser;

use DigitalBibleSociety\DBPDusk\Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class SpecificBiblesTest extends DuskTestCase
{

    /**
     * Run through a few verses
     * ensure they return the right
     * text for that bible
     *
     * @test
     */
    public function checkBibleText(): void
    {
        $this->checkText([
            'ENGESV' => 'Are not the rich the ones who oppress you, and the ones who drag you into court?',
            'RUSS76' => 'А вы презрели бедного. Не богатые ли притесняют вас, и не они ли влекут вас в суды?',
            'ARBVDV' => 'وَأَمَّا أَنْتُمْ فَأَهَنْتُمُ ٱلْفَقِيرَ. أَلَيْسَ ٱلْأَغْنِيَاءُ يَتَسَلَّطُونَ عَلَيْكُمْ وَهُمْ يَجُرُّونَكُمْ إِلَى ٱلْمَحَاكِمِ؟',
            'PORTLH' => 'No entanto, vocês desprezam os pobres. Por acaso, não são os ricos que exploram vocês e os arrastam para serem julgados nos tribunais?',
            'SPAR95' => 'Pero vosotros habéis afrentado al pobre. ¿No os oprimen los ricos y no son ellos los mismos que os arrastran a los tribunales?',
            'DEUL12' => 'Ihr aber habt dem Armen Unehre getan. Sind nicht die Reichen die, die Gewalt an euch üben und ziehen euch vor Gericht?',
            'CMNUNV' => '你 們 反 倒 羞 辱 貧 窮 人 。 那 富 足 人 豈 不 是 欺 壓 你 們 、 拉 你 們 到 公 堂 去 麼 ',
            'THATSV' => 'แต่​พวก​ท่าน​กลับ​ดู‍ถูก​คน‍จน พวก​คน​มั่งมี​ไม่‍ใช่​หรือ​ที่​กด‍ขี่‍ข่ม‍เหง​ท่าน? และ​พวก​เขา​ไม่‍ใช่​หรือ​ที่​ลาก​ตัว​ท่าน​ไป​ขึ้น​ศาล?',
            'TGLTAB' => "Nguni't inyong niwalang-puri ang dukha. Hindi baga kayo'y pinahihirapan ng mayayaman, at sila rin ang kumakaladkad sa inyo sa harapan ng mga hukuman?",
            'KAZKAZ' => 'Ал сендер кедейлерді кемсітіп келесіңдер! Өздеріңді жәбірлеп, сотқа тартатындар сол байлар емес пе?!',
            'HATSBH' => "Men nou menm se meprize n'ap meprize pòv yo! Eske se pa moun rich yo k'ap kraze nou, k'ap trennen nou tribinal?"
        ]);
    }


    /**
     *
     * The provided key, the Bible id, is used to navigate to the passage
     * where the value, the verse text, is checked against the reference.
     *
     * @param $versions
     *
     * @throws \Throwable
     */
    private function checkText($versions): void
    {
        $this->browse(function (Browser $browser) use($versions) {
            foreach ($versions as $id => $text) {
                $browser->visit('bible/'.$id.'/JAS/2')->waitFor('.JAS2_6', 3)->assertSeeIn('.JAS2_6', $text);
            }
        });
    }

}