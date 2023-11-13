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
use App\Service\Client\KeepingClient;
use Symfony\Component\Serializer\SerializerInterface;

use function current;

class KeepingService
{
    public function __construct(private KeepingClient $keepingClient, private SerializerInterface $serializer)
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
        $response = $this->keepingClient->getUser($organisationDto->id);

        $userResponseDto = $this->serializer->deserialize($response->getContent(), UserResponseDto::class, 'json');

        return $userResponseDto->user;
    }

    /** @return ClientDto[] */
    public function getClients(OrganisationDto $organisationDto, string $clientName = 'R2Group'): array
    {
        $response = $this->keepingClient->getClients($organisationDto->id, $clientName);

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
        $response = $this->keepingClient->getOrganisations();

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
        $response = $this->keepingClient->getProjects($organisationDto->id, $clientDto->id, $userDto->id);

        return $this->serializer->deserialize($response->getContent(), ProjectsResponseDto::class, 'json')->projects;
    }

    /** @return TaskDto[] */
    public function getTasks(OrganisationDto $organisationDto, ?ProjectDto $projectDto): array
    {
        $options = $projectDto ? ['query' => ['project_id' => $projectDto->id]] : [];

        /** @return ProjectDto[] */
        $response = $this->keepingClient->getTasks($organisationDto->id, $projectDto->id);

        return $this->serializer->deserialize($response->getContent(), TasksResponseDto::class, 'json')->tasks;
    }
}
