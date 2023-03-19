<?php

namespace App\Controller\Expense;

use App\Entity\User;
use App\Repository\ExpenseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteExpenseController extends AbstractController
{

    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository,
        private readonly UserRepositoryInterface $userRepository
    )
    {
    }

    #[Route('/api/delete/expense', name: 'app_delete_expense', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        /** @var User $user */
        $user = $this->getUser();
        try {
            $expense = $this->expenseRepository->find(id: $data['id']);
            if ($expense) {
                $this->expenseRepository->remove(entity: $expense, flush: true);
                $user->setBalance(balance: $user->getBalance() + $expense->getAmount());
                $this->userRepository->save(entity: $user, flush: true);
                return $this->json(
                    data: ['message' => 'Expense deleted successfully!'],
                    status: Response::HTTP_CREATED
                );
            }
        } catch (\Exception $e) {
            return $this->json(data: ['message' => 'Could not delete expense.'],
                status: Response::HTTP_BAD_REQUEST);
        }

        return $this->json(data: ['message' => 'No expense with that ID.'],
            status: Response::HTTP_BAD_REQUEST);
    }
}
