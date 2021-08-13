<?php

namespace App\Service\Utils;

use App\Model\Exception\HttpRequestException;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface HttpClientInterface
{
    /**
     * @throws HttpRequestException
     */
    public function request(string $method, string $url, array $options = []): ResponseInterface;
}
