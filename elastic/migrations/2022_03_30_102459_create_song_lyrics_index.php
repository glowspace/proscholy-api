<?php
declare(strict_types=1);

use App\SongLyric;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateSongLyricsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::dropIfExists('song_lyrics');

        $sl = new SongLyric();
        // for mapping and settings, see SongLyricSearchableTrait.php
        Index::createRaw('song_lyrics', $sl->getMapping(), $sl->getSettings());
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('song_lyrics');
    }
}
