<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class SyncService
{
    public function __construct(
        private KeepingService $keepingService,
        private ClockifyService $clockifyService,
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke()
    {
        $this->syncroniseAllToKeeping();
    }

    public function syncroniseAllToKeeping()
    {
        $this->syncroniseProjectsToKeeping();

        //get clockify entries
        $entries = $this->clockifyService->getTimeEntriesPreviousMonth();

        //add entries to keeping
    }

    public function syncroniseProjectsToKeeping()
    {
        //get clockify projects
        $clockifyProjectDtos = $this->clockifyService->getProjects();

        //get keeping client by name
        $keepingClientDto = $this->keepingService->getClientByName('ECHC');
        //get keeping projects by code with clockifyProjectId
        $keepingProjectDtos = $this->keepingService->getProjectsByClientDto($keepingClientDto);

        //add project into keeping that doesn't exist yet.
        $keepingProjectDto = $this->keepingService->createProject($keepingClientDto, 'Test Project', 'clockifyId12345');
    }

    // create new client in keeping
    // sync projects from clockify to keeping ECHC
        // store projectID as keeping project code
    // sync time-entries from clockify to keeping

    // fetch time-entries from keeping
    // insert into clockify in correct project
}
