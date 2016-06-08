<?php

namespace spec\BenGorUser\SimpleBusBridge\EventBus;

use BenGorUser\User\Application\Command\SignUp\SignUpUserCommand;
use BenGorUser\User\Domain\Model\Event\UserEvent;
use BenGorUser\User\Domain\Model\Event\UserRegistered;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SimpleBus\Message\Bus\MessageBus;

class SimpleBusUserEventBusSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGorUser\SimpleBusBridge\EventBus\SimpleBusUserEventBus');
        $this->shouldImplement('BenGorUser\User\Infrastructure\Domain\Model\UserEventBus');
    }

    function it_handles_a_command(MessageBus $messageBus, UserEvent $event)
    {
        $messageBus->handle($event)->shouldBeCalled();
        $this->handle($event);
    }
}
