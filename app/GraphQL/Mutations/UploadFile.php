<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class UploadFile
{
    /**
     * Upload a file, store it on the server and return the path.
     *
     * @param  mixed  $root
     * @param  array<string, mixed>  $args
     * @return string|null
     */
    public function __invoke($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo): ?string
    {
        $allow_owerwrite = isset($args['allow_overwrite']) && $args['allow_overwrite'];

        /** @var \Illuminate\Http\UploadedFile $file */
        // file stored in tmp/tempname
        $tempfile = $args['file'];

        $fname = $this->getSlugifiedName($tempfile);

        if (isset($args['filename'])) {
            if (!preg_match('/^[A-Za-z0-9_.-]+$/', $args['filename'])) {
                throw ValidationException::withMessages([
                    'input.filename' => "Název souboru smí obsahovat pouze písmena bez diakritiky, čísla a znaky '-', '_', '.' (bez mezer)"
                ]);
            }

            $fname = $args['filename'];
        }


        if (file_exists(Storage::path("public_files/$fname"))) {
            if ($allow_owerwrite) {
                Storage::delete(Storage::path("public_files/$fname"));
            } else {
                throw ValidationException::withMessages([
                    'input.filename' => "Soubor s daným jménem již existuje, prosím použijte jiné jméno, nebo přepište starý soubor"
                ]);
            }
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
