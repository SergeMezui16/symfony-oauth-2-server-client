<?php

namespace App\EventSubscriber;


use League\Bundle\OAuth2ServerBundle\Event\UserResolveEvent;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class AuthorizationCodeSubscriber
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var PasswordHasherInterface
     */
    private $userPasswordEncoder;

    /**
     * @param UserProviderInterface $userProvider
     * @param PasswordHasherInterface $userPasswordEncoder
     */
    public function __construct(UserProviderInterface $userProvider, PasswordHasherInterface $userPasswordEncoder)
    {
        $this->userProvider = $userProvider;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param UserResolveEvent $event
     */
    public function onUserResolve(UserResolveEvent $event): void
    {
        $user = $this->userProvider->loadUserByUsername($event->getUsername());

        if (null === $user) {
            return;
        }

        if (!$this->userPasswordEncoder->verify($user, $event->getPassword())) {
            return;
        }

        $event->setUser($user);
    }
}
