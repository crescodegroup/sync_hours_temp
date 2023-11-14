<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeZone;
use DateTimeImmutable;
use DateTimeInterface;
use App\Dto\Clockify\User\UserDto;
use App\Service\Client\ClockifyClient;
use App\Dto\Clockify\TimeEntry\TimeEntryDto;
use Symfony\Component\Serializer\SerializerInterface;

class ClockifyService
{
    private ?UserDto $user = null;

    public function __construct(private ClockifyClient $clockifyClient, private SerializerInterface $serializer)
    {
    }

    /** @return TimeEntryDto[] */
    public function getTimeEntriesPreviousMonth(): array
    {
        $now = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $pageNumber = 1;
        $collectTimeEntries = [];
        do {
            $timeEntries = $this->getTimeEntries(
                $now->modify('first day of last month midnight'),
                $now->modify('last day of this month midnight'),
                10,
                $pageNumber++
            );
            $collectTimeEntries = array_merge($collectTimeEntries, $timeEntries);
        } while ($timeEntries);

        return $collectTimeEntries;
    }

    /**
     * @return TimeEntry[]
     */
    private function getTimeEntries(
        DateTimeInterface $start,
        DateTimeInterface $end,
        int $itemsPerPage = 5000,
        int $pageNr = 1
    ): array {
        $response = $this->clockifyClient->getTimeEntries(
            $this->getWorkspaceId(),
            $this->getUserId(),
            [
                'query' => [
                    'page-size' => $itemsPerPage,
                    'page' => $pageNr,
                    'start' => $start->format('Y-m-d\TH:m:s.vp'),
                    'end' => $end->format('Y-m-d\TH:m:s.vp')
                ]
            ]
        );

        return $this->serializer->deserialize($response->getContent(), TimeEntryDto::class . '[]', 'json');
    }

    private function getUser(): UserDto
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $response = $this->clockifyClient->getUser();

        $this->user = $this->serializer->deserialize($response->getContent(), UserDto::class, 'json');

        return $this->user;
    }

    private function getUserId(): string
    {
        return $this->getUser()->id;
    }

    private function getWorkspaceId(): string
    {
        return $this->getUser()->activeWorkspace;
    }
}
