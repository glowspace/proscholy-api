<?php

namespace App\GraphQL\Queries;

use App\Services\LilypondPartsService;
use App\Services\LilypondClientService;

class LilypondGetFile
{
    public function __invoke($rootValue, array $args)
    {
        /** @var LilypondPartsService */
        $lpt_service = app(LilypondPartsService::class);
        /** @var LilypondClientService */
        $lp_service = app(LilypondClientService::class);

        $template = $lpt_service->makeLilypondPartsTemplate(
            $args['lilypond_total']['lilypond_parts'],
            $args['lilypond_total']['global_src'] ?? '',
            $args['lilypond_total']['render_config'] ?? [],
            $args['lilypond_total']['sequence_string'] ?? '');

        $response = [
            'base64' => ''
        ];

        if ($args['file_type'] == 'pdf') {
            $data = $lp_service->doClientRenderPdf($template);
            $response['base64'] = base64_encode($data['pdf']);
        }

        if ($args['file_type'] == 'zip') {
            $response['base64'] = base64_encode(stream_get_contents($template->getZippedSrcStream()));
        }

        return $response;
    }
}
