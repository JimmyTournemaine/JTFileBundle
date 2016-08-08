<?php
namespace MapsBundle\Model;

interface ExpandedLocationable extends Locationable
{
    public function getText();
}