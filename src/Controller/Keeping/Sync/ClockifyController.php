<?php

namespace App\Controller\Keeping\Sync;

use App\Service\KeepingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClockifyController extends AbstractController
{
    public function __construct(private KeepingService $keepingService, private HttpClientInterface $clockifyClient)
    {
    }

    #[Route('/keeping/sync/clockify', name: 'app_keeping_sync_clockify', format: 'json')]
    public function index(): JsonResponse
    {
        return $this->json($this->keepingService->__invoke());
    }
}
