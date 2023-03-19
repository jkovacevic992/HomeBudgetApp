<?php

namespace App\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/expenses', name: 'app_get_expenses', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $requestParameters = $request->query->all();
        $category = null;
        $min = null;
        $max = null;
        $date = null;

        if (array_key_exists('category', $requestParameters)) {
            $category = $requestParameters['category'];
        }
        if (array_key_exists('min', $requestParameters)) {
            $min = $requestParameters['min'];
        }
        if (array_key_exists('max', $requestParameters)) {
            $max = $requestParameters['max'];
        }
        if (array_key_exists('date', $requestParameters)) {
            $date = $requestParameters['date'];
        }
        /** @var User $user */
        $user = $this->getUser();

        $expenses = $this->expenseRepository->findExpenses(
            userId: $user->getId(),
            category: $category,
            min: $min,
            max: $max,
            date: $date
        );

        return $this->json(data: $expenses);
    }
}
