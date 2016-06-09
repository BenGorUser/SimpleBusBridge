<?php

namespace spec\BenGorUser\SimpleBusBridge\CommandBus;

use BenGorUser\SimpleBusBridge\CommandBus\SimpleBusUserCommandBus;
use BenGorUser\User\Application\Command\SignUp\SignUpUserCommand;
use BenGorUser\User\Infrastructure\CommandBus\UserCommandBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SimpleBus\Message\Bus\MessageBus;

/**
 * Spec file of SimpleBusUserCommandBus class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SimpleBusUserCommandBusSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SimpleBusUserCommandBus::class);
    }

    function it_implements_user_command_bus()
    {
        $this->shouldImplement(UserCommandBus::class);
    }

    function it_handles_a_command(MessageBus $messageBus, SignUpUserCommand $command)
    {
        $messageBus->handle($command)->shouldBeCalled();

        $this->handle($command);
    }
}
