<?php

namespace spec\BenGorUser\SimpleBusBridge\Builder;

use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventBusBuilderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BenGorUser\SimpleBusBridge\Builder\EventBusBuilder');
    }

    function it_returns_built_event_bus()
    {
        $this->eventBus()->shouldReturnAnInstanceOf(UserEventBus::class);
    }
}
