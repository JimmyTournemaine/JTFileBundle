<?php
namespace MapsBundle\Model;

interface Locationable {
    public function getLatitude();
    public function getLongitude();
    public function isValid();
}