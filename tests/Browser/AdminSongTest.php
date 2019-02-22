<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;

class AdminSongTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp() : void
    {
        parent::setUp();
        $this->seed('UserSeeder');
    }

    public function testCreateNewSong()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::first());
            // $browser->assertAuthenticated();
            $browser->visit('/admin/song/new')
                    ->assertSee('Nová píseň')
                    ->type('name', 'new_song')
                    ->press('ULOŽIT');
        });

        $this->assertDatabaseHas('song_lyrics', [
            'name' => 'new_song'
        ]);
    }

    public function testEditNewSong()
    {
        $this->browse(function (Browser $browser) {
            // $browser->loginAs(User::find(1));
            $browser->visit('/admin/song/new')
                    ->assertSee('Nová píseň')
                    ->type('name', 'new_s')
                    ->press('ULOŽIT A UPRAVIT')
                    ->assertSee('Úprava písně')
                    ->press('ULOŽIT');
        });

        $this->assertDatabaseHas('song_lyrics', [
            'name' => 'new_s'
        ]);
    }
}
