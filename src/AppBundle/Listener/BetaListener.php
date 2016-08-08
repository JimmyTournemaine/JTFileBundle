<?php
namespace AppBundle\Listener;

use AppBundle\Service\BetaHTML;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener {

    private $betaHTML;
    private $remainingDays;

    public function __construct(BetaHTML $beta, $endDate)
    {
        $this->betaHTML = $beta;
        $this->remainingDays = (new \DateTime($endDate))->diff(new \DateTime())->days;
    }

    public function processBeta(FilterResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$event->getRequest()->isXmlHttpRequest() || $this->remainingDays <= 0){
            return;
        }

        $event->setResponse($this->betaHTML->displayBeta($event->getResponse(), $this->remainingDays));
    }
}