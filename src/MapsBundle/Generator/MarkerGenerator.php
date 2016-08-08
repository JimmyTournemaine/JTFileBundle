<?php
namespace MapsBundle\Generator;

use Fungio\GoogleMap\Overlays\Animation;
use Fungio\GoogleMap\Overlays\Marker;
use MapsBundle\Model\Locationable;
use MapsBundle\Model\ExpandedLocationable;

class MarkerGenerator
{
    static public function generateFromLocation(Locationable $location)
    {
        return (new Marker())
            ->setPosition($location->getLatitude(), $location->getLongitude())
            ->setAnimation(Animation::DROP)
        ;
    }

    static public function generateClickableFromLocation(ExpandedLocationable $location)
    {
        return self::generateFromLocation($location)
            ->setOption('clickable', true)
            ->setInfoWindow(InfoWindowGenerator::generateFromHtml($location->setText()))
        ;
    }
}