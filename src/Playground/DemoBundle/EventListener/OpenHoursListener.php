<?php

namespace Playground\DemoBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class OpenHoursListener implements EventSubscriberInterface
{
    private $openHour;
    private $closeHour;

    public function __construct($openHour, $closeHour)
    {
        $this->openHour = $openHour;
        $this->closeHour = $closeHour;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $currentHour = date('H');
        if ($currentHour < $this->openHour || $currentHour > $this->closeHour) {

            // setting a response stops the propagation of the kernel.request event
            $event->setResponse(new Response('Sorry the website is now closed.'));
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array('onKernelRequest', 255),
        );
    }
}