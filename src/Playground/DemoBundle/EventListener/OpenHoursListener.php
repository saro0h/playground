<?php

namespace Playground\DemoBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class OpenHoursListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $currentHour = date('H');
        if ($currentHour < 8 || $currentHour > 18 ) {

            // setting a response stops the propagation of the kernel.request event
            $event->setResponse(new Response('Sorry the website is now closed.'));
        }
    }
}