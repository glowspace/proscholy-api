<?php
declare(strict_types=1);

use App\Author;
use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateAuthorsIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::dropIfExists('authors');

        $author = new Author();
        // for mapping and settings, see SongLyricSearchableTrait.php
        Index::createRaw('authors', $author->getMapping(), $author->getSettings());
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('authors');
    }
}
