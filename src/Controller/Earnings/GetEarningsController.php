<?php

namespace App\Controller\Earnings;

use App\Entity\User;
use App\Repository\EarningsRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetEarningsController extends AbstractController
{

    /**
     * @param EarningsRepositoryInterface $earningsRepository
     */
    public function __construct(private readonly EarningsRepositoryInterface $earningsRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    #[Route('/api/earnings', name: 'app_get_earnings', methods: ['GET'])]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $earnings = $this->earningsRepository->findByUserId($user->getId());

        if ($earnings) {
            return $this->json(data: $earnings);
        }
        return $this->json(data: 'No earnings found for user.');
    }
}
