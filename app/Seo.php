<?php

namespace App;

use DiDom\Document;

class Seo
{
    public static function parseSeoHtml($htmlPage)
    {
        $document = new Document($htmlPage);
        $h1Html = $document->first('h1');
        $h1 = $h1Html ? $h1Html->text() : '';
        $descriptionHtml = $document->first('meta[name=description]');
        $description = $descriptionHtml ? $descriptionHtml->getAttribute('content') : '';
        $keywordsHtml = $document->first('meta[name=keywords]');
        $keywords = $keywordsHtml ? $keywordsHtml->getAttribute('content') : '';
        return [
            'h1' => $h1,
            'description' => $description,
            'keywords' => $keywords
        ];
    }
}
