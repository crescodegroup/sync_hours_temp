<?php

namespace App\Controller\Keeping\Sync;

use App\Service\KeepingService;
use App\Service\SyncService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ClockifyController extends AbstractController
{
    public function __construct(private KeepingService $keepingService, private SyncService $syncService)
    {
    }

    #[Route('/keeping/sync/clockify', name: 'app_keeping_sync_clockify', format: 'json')]
    public function index(): JsonResponse
    {
        //still in testing fase
        $this->syncService->__invoke();

        return $this->json($this->keepingService->__invoke());
    }
}
