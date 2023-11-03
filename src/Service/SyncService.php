<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Clockify\TimeEntry\TimeEntryDto;
use App\Dto\Clockify\User\UserDto;
use App\Service\Client\ClockifyClient;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Serializer\SerializerInterface;

class SyncService
{
    private ?UserDto $clockifyUser = null;

    public function __construct(
        private KeepingService $keepingService,
        private ClockifyClient $clockifyClient,
        private SerializerInterface $serializer,
    ) {
    }

    public function __invoke()
    {
        $this->getClockifyUser();
        $result = $this->getClockifyTimeEntriesPreviousMonth();

        dd($this->clockifyUser, $result);
    }

    private function getClockifyUser(): UserDto
    {
        if ($this->clockifyUser !== null) {
            return $this->clockifyUser;
        }

        $response = $this->clockifyClient->getUser();

        $this->clockifyUser = $this->serializer->deserialize($response->getContent(), UserDto::class, 'json');

        return $this->clockifyUser;
    }

    /** @return TimeEntryDto[] */
    private function getClockifyTimeEntriesPreviousMonth(): array
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $start = $now->modify('first day of last month midnight');
        $end = $now->modify('last day of this month midnight');

        $response = $this->clockifyClient->getTimeEntries(
            $this->clockifyUser->activeWorkspace,
            $this->clockifyUser->id,
            [
                'query' => [
                    'page-size' => '5000',
                    'start' => $start->format('Y-m-d\TH:m:s.vp'),
                    'end' => $end->format('Y-m-d\TH:m:s.vp')
                ]
            ]
        );

        return $this->serializer->deserialize($response->getContent(), TimeEntryDto::class . '[]', 'json');
    }
}
