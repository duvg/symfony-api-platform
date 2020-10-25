<?php

declare(strict_types=1);

namespace App\Api\Listener;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user  = $event->getUser();
        $payload = $event->getData();

        // Add and remove custom data
        $payload['id'] = $user->getId();
        unset($payload['roles']);

        $event->setData($payload);

    }

}