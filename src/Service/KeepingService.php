<?php

namespace App\Service;

use App\Dto\Keeping\ClientDto;
use App\Dto\Keeping\OrganisationDto;
use App\Dto\Keeping\ProjectDto;
use App\Dto\Keeping\Response\ClientsResponseDto;
use App\Dto\Keeping\Response\OrganisationsResponseDto;
use App\Dto\Keeping\Response\ProjectsResponseDto;
use App\Dto\Keeping\Response\TasksResponseDto;
use App\Dto\Keeping\Response\UserResponseDto;
use App\Dto\Keeping\TaskDto;
use App\Dto\Keeping\UserDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class KeepingService
{
    public function __construct(private SerializerInterface $serializer, private HttpClientInterface $keepingClient)
    {
    }

    public function __invoke()
    {
        $organisationDtos = $this->getOrganisations();
        $organisationDto = current($organisationDtos);
        $clientDtos = $this->getClients($organisationDto, 'R2Group');
        $clientDto = current($clientDtos);
        $userDto = $this->getUser($organisationDto);
        $projectDtos = $this->getProjects($organisationDto, $clientDto, $userDto);
        $projectDto = current($projectDtos);
        $taskDtos = $this->getTasks($organisationDto, $projectDto);

        return $taskDtos;
    }

    public function getUser(OrganisationDto $organisationDto): UserDto
    {
        $response = $this->keepingClient->request(
            Request::METHOD_GET,
            sprintf('%d/users/me', $organisationDto->id)
        );

        $userResponseDto = $this->serializer->deserialize($response->getContent(), UserResponseDto::class, 'json');

        return $userResponseDto->user;
    }

    /** @return ClientDto[] */
    public function getClients(OrganisationDto $organisationDto, string $clientName = 'R2Group'): array
    {
        $response = $this->keepingClient->request(
            Request::METHOD_GET,
            sprintf('%d/clients', $organisationDto->id),
            [
                'query' => [
                    'state' => ['active'],
                    'search_query' => 'R2Group'
                ]
            ]
        );

        $clientsResponseDto = $this->serializer->deserialize(
            $response->getContent(),
            ClientsResponseDto::class,
            'json'
        );

        return $clientsResponseDto->clients;
    }

    /** @return OrganisationDto[] */
    public function getOrganisations(): array
    {
        $response = $this->keepingClient->request(Request::METHOD_GET, 'organisations');

        $organisations = $this->serializer->deserialize(
            $response->getContent(),
            OrganisationsResponseDto::class,
            'json'
        );

        return $organisations->organisations;
    }

    /** @return ProjectDto[] */
    public function getProjects(OrganisationDto $organisationDto, ClientDto $clientDto, UserDto $userDto): array
    {
        $response = $this->keepingClient->request(
            Request::METHOD_GET,
            sprintf('%d/projects', $organisationDto->id),
            [
                'query' => [
                    'user_id' => $userDto->id,
                    'client_id' => $clientDto->id,
                    'state' => ['active']
                ]
            ],
        );

        return $this->serializer->deserialize($response->getContent(), ProjectsResponseDto::class, 'json')->projects;
    }

    /** @return TaskDto[] */
    public function getTasks(OrganisationDto $organisationDto, ?ProjectDto $projectDto): array
    {
        $options = $projectDto ? ['query' => ['project_id' => $projectDto->id]] : [];

        /** @return ProjectDto[] */
        $response = $this->keepingClient->request(
            Request::METHOD_GET,
            sprintf('%d/tasks', $organisationDto->id),
            $options,
        );

        return $this->serializer->deserialize($response->getContent(), TasksResponseDto::class, 'json')->tasks;
    }
}
