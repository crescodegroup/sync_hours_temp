<?php

declare(strict_types=1);

namespace App\Service\Client;

use App\Dto\Keeping\Request\ProjectRequestDto;
use Symfony\Component\HttpClient\DecoratorTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class KeepingClient implements HttpClientInterface
{
    use DecoratorTrait;

    public function __construct(private HttpClientInterface $keepingClient)
    {
        $this->client = $keepingClient;
    }

    public function getUser(int $organisationId): ResponseInterface
    {
        return $this->client->request(
            Request::METHOD_GET,
            sprintf('%d/users/me', $organisationId)
        );
    }

    public function getClients(int $organisationId, ?string $searchQuery = null): ResponseInterface
    {
        return $this->client->request(
            Request::METHOD_GET,
            sprintf('%d/clients', $organisationId),
            [
                'query' => [
                    'state' => ['active'],
                    'search_query' => $searchQuery
                ]
            ]
        );
    }

    public function getOrganisations(): ResponseInterface
    {
        return $this->keepingClient->request(Request::METHOD_GET, 'organisations');
    }

    public function getProjects(int $organisationId, int $clientId, int $userId): ResponseInterface
    {
        return $this->client->request(
            Request::METHOD_GET,
            sprintf('%d/projects', $organisationId),
            [
                'query' => [
                    'user_id' => $userId,
                    'client_id' => $clientId,
                    'state' => ['active']
                ]
            ],
        );
    }

    public function createProject(int $organisationId, array $body = []): ResponseInterface
    {
        return $this->client->request(
            Request::METHOD_POST,
            sprintf('%d/projects', $organisationId),
            [
                'json' => $body
            ]
        );
    }

    public function deleteProject(int $organisationId, int $projectId): ResponseInterface
    {
        return $this->client->request(
            Request::METHOD_DELETE,
            sprintf('%d/projects/%d', $organisationId, $projectId)
        );
    }

    public function getTasks(int $organisationId, ?int $projectId)
    {
        $options = $projectId ? ['query' => ['project_id' => $projectId]] : [];

        return $this->keepingClient->request(
            Request::METHOD_GET,
            sprintf('%d/tasks', $organisationId),
            $options,
        );
    }
}
