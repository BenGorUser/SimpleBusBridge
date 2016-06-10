<?php

namespace BenGorUser\SimpleBusBridge\Builder;

use BenGorUser\SimpleBusBridge\CommandBus\SimpleBusUserCommandBus;
use BenGorUser\SimpleBusBridge\EventBus\SimpleBusUserEventBus;

use BenGorUser\User\Application\Command\ChangePassword\ChangeUserPasswordCommand;
use BenGorUser\User\Application\Command\ChangePassword\ChangeUserPasswordHandler;
use BenGorUser\User\Application\Command\Enable\EnableUserCommand;
use BenGorUser\User\Application\Command\Enable\EnableUserHandler;
use BenGorUser\User\Application\Command\LogIn\LogInUserCommand;
use BenGorUser\User\Application\Command\LogIn\LogInUserHandler;
use BenGorUser\User\Application\Command\LogOut\LogOutUserCommand;
use BenGorUser\User\Application\Command\LogOut\LogOutUserHandler;
use BenGorUser\User\Application\Command\Remove\RemoveUserCommand;
use BenGorUser\User\Application\Command\Remove\RemoveUserHandler;
use BenGorUser\User\Application\Command\SignUp\SignUpUserCommand;
use BenGorUser\User\Application\Command\SignUp\SignUpUserHandler;

use BenGorUser\User\Domain\Model\User;
use BenGorUser\User\Domain\Model\UserFactorySignUp;
use BenGorUser\User\Domain\Model\UserPasswordEncoder;
use BenGorUser\User\Domain\Model\UserRepository;

use BenGorUser\User\Infrastructure\Domain\Model\UserEventBus;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Recorder\AggregatesRecordedMessages;
use SimpleBus\Message\Recorder\HandlesRecordedMessagesMiddleware;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;

/**
 * Class BusBuilder.
 *
 * @package BenGorUser\SimpleBusBridge\Builder
 */
class CommandBusBuilder
{
    protected $commandBus;

    public function __construct(SimpleBusUserEventBus $eventBus,
                                UserRepository $repository,
                                UserPasswordEncoder $encoder,
                                UserFactorySignUp $factory)
    {
        $handlers = [
            LogInUserCommand::class          => 'bengor.user.application.command.log_in_user',
            LogOutUserCommand::class         => 'bengor.user.application.command.log_out_user',
            EnableUserCommand::class         => 'bengor.user.application.command.enable_user',
            SignUpUserCommand::class         => 'bengor.user.application.command.sign_up_user',
            ChangeUserPasswordCommand::class => 'bengor.user.application.command.change_user_password',
            RemoveUserCommand::class         => 'bengor.user.application.command.remove_user',

        ];

        $serviceLocator = function ($serviceId) use ($repository, $encoder, $factory) {
            $services = [
                'bengor.user.application.command.log_in_user'          => new LogInUserHandler($repository, $encoder),
                'bengor.user.application.command.log_out_user'         => new LogOutUserHandler($repository),
                'bengor.user.application.command.enable_user'          => new EnableUserHandler($repository),
                'bengor.user.application.command.sign_up_user'         => new SignUpUserHandler($repository, $encoder, $factory),
                'bengor.user.application.command.change_user_password' => new ChangeUserPasswordHandler($repository, $encoder),
                'bengor.user.application.command.remove_user'          => new RemoveUserHandler($repository),
            ];

            return $services[$serviceId];
        };

        // Middlewares
        $finishesHandling = new FinishesHandlingMessageBeforeHandlingNext();

        $handlesRecorded = new HandlesRecordedMessagesMiddleware(
            new AggregatesRecordedMessages([]),
            $this->getRawEventBus($eventBus)
        );

        $notifiesMessageSubscribers = new NotifiesMessageSubscribersMiddleware(
            new NameBasedMessageSubscriberResolver(
                new ClassBasedNameResolver(),
                new CallableCollection([], new ServiceLocatorAwareCallableResolver($serviceLocator))
            )
        );

        $delegateHandler = new DelegatesToMessageHandlerMiddleware(
            new NameBasedMessageHandlerResolver(
                new ClassBasedNameResolver(),
                new CallableMap(
                    $handlers,
                    new ServiceLocatorAwareCallableResolver($serviceLocator)
                )
            )
        );

        // The command bus
        $this->commandBus = new SimpleBusUserCommandBus(
            new MessageBusSupportingMiddleware([
                $finishesHandling,
                $handlesRecorded,
                $notifiesMessageSubscribers,
                $delegateHandler,
            ])
        );
    }

    public function commandBus()
    {
        return $this->commandBus;
    }

    private function getRawEventBus(SimpleBusUserEventBus $eventBus)
    {
        $reflectionClass = new \ReflectionClass(SimpleBusUserEventBus::class);
        $property = $reflectionClass->getProperty('messageBus');
        $property->setAccessible(true);
        return $property->getValue($eventBus);
    }
}
