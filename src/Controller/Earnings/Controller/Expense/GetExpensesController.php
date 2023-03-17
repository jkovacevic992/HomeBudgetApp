<?php

namespace App\Controller\Earnings\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetExpensesController extends AbstractController
{

    /**
     * @param ExpenseRepositoryInterface $expenseRepository
     */
    public function __construct(private readonly ExpenseRepositoryInterface $expenseRepository)
    {
    }

    /**
     * @return JsonResponse
     */
    #[Route('/api/expenses', name: 'app_get_expenses', methods: ['GET'])]
    public function index(): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();

        $expenses = $this->expenseRepository->findByUserId($user->getId());

        return $this->json(data: $expenses);
    }
}
