<?php

namespace App\GraphQL\Mutations;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Execution\ErrorBuffer;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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
        $validationErrorBuffer = (new ErrorBuffer)->setErrorType('validation');
        // // $validatorCustomAttributes = ['resolveInfo' => $resolveInfo, 'context' => $context, 'root' => $root];

        /** @var \Illuminate\Http\UploadedFile $file */
        // file stored in tmp/tempname
        $tempfile = $args['file'];

        $fname = $this->getSlugifiedName($tempfile);

        if (isset($args['filename'])) {
            if (!preg_match('/^[A-Za-z0-9_.-]+$/', $args['filename'])) {
                $validationErrorBuffer->push("Název souboru musí obsahovat pouze písmena, čísla a znaky ',', '_', '.' (bez mezer)", "input.filename");
                $validationErrorBuffer->flush(
                    "Validation failed for the field [input.filename]"
                );
            }

            $fname = $args['filename'];
        }

        if (file_exists(Storage::path("public_files/$fname"))) {
            // the file already exists, return error
            $validationErrorBuffer->push("Soubor s daným jménem již existuje, prosím použijte jiné jméno", "input.filename");
            $validationErrorBuffer->flush(
                "Validation failed for the field [input.filename]"
            );
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
