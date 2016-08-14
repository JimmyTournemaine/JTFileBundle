<?php
namespace AppBundle\Twig;

use AppBundle\Service\Flashify;

class AppExtension extends \Twig_Extension
{
    private $flashify;

    public function __construct(Flashify $flash)
    {
        $this->flashify = $flash;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('flash', array($this, 'flashFunction'))
        );
    }

    public function flashFunction($type, $message)
    {
        return $this->flashify->toFlash($type, $message);
    }

    public function getName()
    {
        return 'app_extension';
    }
}