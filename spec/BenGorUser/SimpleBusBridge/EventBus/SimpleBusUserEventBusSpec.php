<?php

namespace spec\BenGorUser\SimpleBusBridge\EventBus;

use BenGorUser\SimpleBusBridge\EventBus\SimpleBusUserEventBus;
use BenGorUser\User\Application\Command\SignUp\SignUpUserCommand;
use BenGorUser\User\Domain\Model\Event\UserEvent;
use BenGorUser\User\Domain\Model\Event\UserRegistered;
use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SimpleBus\Message\Bus\MessageBus;

/**
 * Spec file of SimpleBusUserEventBus class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SimpleBusUserEventBusSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusUserEventBus::class);
    }

    function it_implements_user_event_bus()
    {
        $this->shouldImplement(UserEventBus::class);
    }

    function it_handles_a_command(MessageBus $messageBus, UserEvent $event)
    {
        $messageBus->handle($event)->shouldBeCalled();

        $this->handle($event);
    }
}
