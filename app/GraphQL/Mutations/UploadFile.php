<?php

namespace App\GraphQL\Mutations;

use Illuminate\Support\Str;

class UploadFile
{
    /**
     * Upload a file, store it on the server and return the path.
     *
     * @param  mixed  $root
     * @param  array<string, mixed>  $args
     * @return string|null
     */
    public function __invoke($root, array $args): ?string
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        // file stored in tmp/tempname
        $tempfile = $args['file'];

        $fname = $this->getSlugifiedName($tempfile);

        if (isset($args['filename'])) {
            $fname = $args['filename'];
        }

        return $tempfile->storePubliclyAs('public_files', $fname);
    }

    private function getSlugifiedName($file)
    {
        $fullname = $file->getClientOriginalName();
        $filename = pathinfo($fullname, PATHINFO_FILENAME);
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);

        return Str::slug($filename, '-') . '.' . $extension;
    }
}
