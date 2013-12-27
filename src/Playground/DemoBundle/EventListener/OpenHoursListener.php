<?php

namespace Playground\DemoBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Playground\DemoBundle\Event\TriggerTimeEvent;

class OpenHoursListener implements EventSubscriberInterface
{
    private $openHour;
    private $closeHour;
    private $dispatcher;

    public function __construct($openHour, $closeHour, EventDispatcherInterface $dispatcher)
    {
        $this->openHour = $openHour;
        $this->closeHour = $closeHour;
        $this->dispatcher = $dispatcher;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $this->dispatchTriggerTime($this->closeHour);

        $currentDate = date('Y-m-d');
        $currentTime = date('H:i');
        $maxTime = date($this->closeHour.':00');

        $dt1 = new \Datetime($currentDate.' '.$currentTime);
        $dt2 = new \Datetime($currentDate.' '.$maxTime);

        $diff = abs($dt1->format('U') - $dt2->format('U'));

        if (($diff/60) <= 30) {
            $this->dispatcher->dispatch(TriggerTimeEvent::TRIGGER_TIME, new TriggerTimeEvent(time()));
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