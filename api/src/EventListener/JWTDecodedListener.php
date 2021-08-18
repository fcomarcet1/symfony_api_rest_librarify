<?php

declare(strict_types=1);

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTDecodedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

class JWTDecodedListener
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onJWTDecoded(JWTDecodedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $payload = $event->getPayload();

        // Check if Ip client its same from token ip
        if (!isset($payload['ip']) || $payload['ip'] !== $request->getClientIp()) {
            $event->markAsInvalid();
        }
    }
}
