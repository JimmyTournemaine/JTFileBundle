<?php
namespace MapsBundle\Model;

abstract class Location implements Locationable, \JsonSerializable
{
    protected $latitude;
    protected $longitude;

    /**
     *
     * {@inheritDoc}
     *
     * @see \MapsBundle\Model\Locationable::getLatitude()
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \MapsBundle\Model\Locationable::getLongitude()
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     *
     * {@inheritDoc}
     *
     * @see \MapsBundle\Model\Locationable::isValid()
     */
    public function isValid()
    {
        return ($this->latitude && $this->longitude);
    }
}