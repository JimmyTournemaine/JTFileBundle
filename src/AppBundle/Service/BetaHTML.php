<?php
namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\DataCollectorTranslator;

class BetaHTML
{
    private $flashify;
    private $translator;

    public function __construct(Flashify $flashify, DataCollectorTranslator $dct)
    {
        $this->flashify = $flashify;
        $this->translator = $dct;
    }

    public function displayBeta(Response $response, $remainingDays)
    {
        return $response->setContent(preg_replace('#<div id="flashes">#iU', '<div id="flashes">' . $this->flashify->toFlash('info', $this->translator->trans('beta', array('%days%' => (int)$remainingDays))), $response->getContent(), 1));
    }
}