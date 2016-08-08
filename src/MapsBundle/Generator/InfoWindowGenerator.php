<?php
namespace MapsBundle\Generator;

use Fungio\GoogleMap\Overlays\InfoWindow;

class InfoWindowGenerator
{
    static public function generateFromHtml($html)
    {
        return (new InfoWindow())
            ->setContent($html)
        ;
    }
}