<?php

declare(strict_types=1);

namespace App\Service\Client;

use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class ClockifyClient implements HttpClientInterface
{
    use DecoratorTrait;

    public function __construct(private HttpClientInterface $clockifyClient)
    {
        $this->client = $clockifyClient;
    }

    public function __invoke()
    {
        $this->getUser();
    }

    public function getUser(): ResponseInterface
    {
        return $this->request(Request::METHOD_GET, 'user');
    }

    public function getTimeEntries(string $workspaceId, string $userId, array $queryParams = []): ResponseInterface
    {
        return $this->request(
            Request::METHOD_GET,
            sprintf('workspaces/%s/user/%s/time-entries', $workspaceId, $userId),
            $queryParams
        );
    }

    public function getProjects(string $workspaceId, array $queryParams = []): ResponseInterface
    {
        return $this->request(
            Request::METHOD_GET,
            sprintf('workspaces/%s/projects', $workspaceId),
            $queryParams
        );
    }
}
