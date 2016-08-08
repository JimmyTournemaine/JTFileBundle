<?php
namespace AppBundle\Service;

class Flashify
{

    private $icons = array(
        'success' => 'ok-sign',
        'warning' => 'warning-sign',
        'danger' => 'remove-sign',
        'info' => 'info-sign',
        'notice' => 'info-sign'
    );

    public function toFlash($type, $message)
    {
        return '
            <div class="alert alert-' . $type . ' alert-dismissible" role="alert">
            <i class="glyphicon glyphicon-' . $this->findIcon($type) . '" aria-hidden="true"></i>
            ' . $message . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>';
    }

    private function findIcon($type)
    {
        $value = array_search($type, $this->icons);
        if ($value === false) {
            $value = 'info-sign';
        }
        return $value;
    }
}