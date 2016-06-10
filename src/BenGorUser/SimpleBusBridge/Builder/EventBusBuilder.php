<?php

namespace BenGorUser\SimpleBusBridge\Builder;

use BenGorUser\SimpleBusBridge\EventBus\SimpleBusUserEventBus;
use BenGorUser\User\Domain\Model\Event\UserEvent;
use SimpleBus\Message\Bus\MessageBus;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class EventBusBuilder
{
    private $eventBus;

    public function __construct()
    {
        $this->eventBus = new SimpleBusUserEventBus(
            new MessageBusSupportingMiddleware([

            ])
        );
    }

    public function eventBus()
    {
        return $this->eventBus;
    }
}
