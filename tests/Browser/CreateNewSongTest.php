<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateNewSongTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/song/new')
                    ->assertSee('Nová píseň');
        });
    }

    public function testExampleTwo()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/author/new')
                    ->assertSee('Nová píseň');
        });
    }
}
