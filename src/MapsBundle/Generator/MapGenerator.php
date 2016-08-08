<?php
namespace MapsBundle\Generator;

use Fungio\GoogleMapBundle\Model\Map;
use MapsBundle\Model\Locationable;

class MapGenerator
{
    private $map;

    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    public function generate(Locationable $location)
    {
        $marker = new Marker();
        $marker->setPosition($location->getLatitude(), $location->getLongitude());
    }


}