<?php

namespace App\Controller\Earnings;

use App\Entity\User;
use App\Repository\EarningsRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetEarningsController extends AbstractController
{

    public function __construct(private readonly EarningsRepositoryInterface $earningsRepository)
    {
    }

    #[Route('/api/earnings', name: 'app_get_earnings', methods: ['GET'])]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $earnings = $this->earningsRepository->findByUserId($user->getId());

        return $this->json(data: $earnings);
    }
}