<?php

namespace spec\BenGorUser\SimpleBusBridge\CommandBus;

use BenGorUser\User\Application\Command\SignUp\SignUpUserCommand;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SimpleBus\Message\Bus\MessageBus;

class SimpleBusUserCommandBusSpec extends ObjectBehavior
{
    function let(MessageBus $messageBus)
    {
        $this->beConstructedWith($messageBus);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('BenGorUser\SimpleBusBridge\CommandBus\SimpleBusUserCommandBus');
        $this->shouldImplement('BenGorUser\User\Infrastructure\CommandBus\UserCommandBus');
    }

    function it_handles_a_command(MessageBus $messageBus, SignUpUserCommand $command)
    {
        $messageBus->handle($command)->shouldBeCalled();
        $this->handle($command);
    }
}
