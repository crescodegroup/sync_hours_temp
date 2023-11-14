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

        $entries = $this->clockifyService->getTimeEntriesPreviousMonth();
    }

    // create new client in keeping
    // sync projects from clockify to keeping ECHC
        // store projectID as keeping project code
    // sync time-entries from clockify to keeping

    // fetch time-entries from keeping
    // insert into clockify in correct project
}
