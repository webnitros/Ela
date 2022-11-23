<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 06.11.2022
 * Time: 14:32
 */

namespace Ela\Events;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class StoreSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                ['onKernelResponsePre', 10],
                ['onKernelResponsePost', -10],
            ],
            //OrderPlacedEvent::NAME => 'onStoreOrder',
        ];
    }

    public function onKernelResponsePre(ResponseEvent $event)
    {
        $request = $event->getRequest();
        $email = $request->get('email');

        return true;
        #$event->stopPropagation();
        // ...
    }

    public function onKernelResponsePost(ResponseEvent $event)
    {
        #$event = new OrderPlacedEvent();
        #app('dispatcher')->dispatch($event, OrderPlacedEvent::NAME);
    }

}
