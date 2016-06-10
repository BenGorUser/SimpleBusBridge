<?php

namespace spec\BenGorUser\SimpleBusBridge\Builder;

use BenGorUser\SimpleBusBridge\EventBus\SimpleBusUserEventBus;
use BenGorUser\User\Domain\Model\UserFactorySignUp;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;
use BenGorUser\User\Infrastructure\CommandBus\UserCommandBus;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CommandBusBuilderSpec extends ObjectBehavior
{
    function let(
        SimpleBusUserEventBus $eventBus,
        UserRepository $repository,
        UserPasswordEncoder $encoder,
        UserFactorySignUp $factory
    ) {
        $this->beConstructedWith($eventBus, $repository, $encoder, $factory);
    }

    function it_is_initializable_with_handlers()
    {
        $this->shouldHaveType('BengorUser\SimpleBusBridge\Builder\CommandBusBuilder');
    }

    function it_returns_built_command_bus()
    {
        $this->commandBus()->shouldReturnAnInstanceOf(UserCommandBus::class);
    }
}
