<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTCreatedListener
{
    private $requestStack;
    private $em;

    public function __construct(RequestStack $requestStack, \Doctrine\ORM\EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }

    public function onJWTCreated(JWTCreatedEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $userRepository = $this->em->getRepository(User::class);

        $payload = $event->getData();

        // Si queremos recoger el user del evento necesitammos modificar la interface y crear un getId(): UuidInterface
        // por eso estoy usando el repository
        $user = $userRepository->findOneBy(['email' => $payload['username']]);

        $payload['sub'] = $user->getId();
        $payload['ip'] = $request->getClientIp();
        //$payload['iat'] = time();

        $event->setData($payload);
    }
}
