<?php

namespace App\Controller\Expense;

use App\Entity\User;
use App\Factory\ExpenseFactory;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\ExpenseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Validator\ExpenseValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddExpenseController extends AbstractController
{
    /**
     * @param ExpenseRepositoryInterface $expenseRepository
     * @param UserRepositoryInterface $userRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param ExpenseValidator $expenseValidator
     */
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly ExpenseValidator $expenseValidator
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/add/expense', name: 'app_add_expense_controller', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);
        $errors = $this->expenseValidator->validateAdd($data);
        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }
        /** @var User $user */
        $user = $this->getUser();
        $category = $this->categoryRepository->find($data['category_id']);
        if (!$category) {
            return $this->json(
                data: ['message' => 'No category with that ID.'],
                status: Response::HTTP_BAD_REQUEST
            );
        }
        $expense = ExpenseFactory::create();
        $expense->setDescription($data['description']);
        $expense->setUser($user);
        $expense->setCategory($category);
        $expense->setAmount($data['amount']);

        $user->setBalance(balance: $user->getBalance() - $data['amount']);

        try {
            $this->expenseRepository->save(entity: $expense, flush: true);
            $this->userRepository->save(entity:  $user, flush: true);
        } catch (\Exception $exception) {
            return $this->json(
                data: ['message' => 'Error when trying to add expense'],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(
            data: ['message' => 'Added new expense successfully'],
            status: Response::HTTP_CREATED
        );
    }
}
