<?php

namespace App\Controller\Earnings\Controller\Expense;

use App\Entity\User;
use App\Repository\CategoryRepositoryInterface;
use App\Repository\ExpenseRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateExpenseController extends AbstractController
{

    /**
     * @param ExpenseRepositoryInterface $expenseRepository
     * @param UserRepositoryInterface $userRepository
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        private readonly ExpenseRepositoryInterface $expenseRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CategoryRepositoryInterface $categoryRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/update/expense', name: 'app_update_expense', methods: ['PUT'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        /** @var User $user */
        $user = $this->getUser();

        $expense = $this->expenseRepository->find($data['id']);

        if (array_key_exists(key: 'amount', array: $data)) {
            $diff = $data['amount'] - $expense->getAmount();
            if ($diff !== 0) {
                $expense->setAmount(amount: $data['amount']);
                $user->setBalance(balance: $user->getBalance() - $diff);
                try {
                    $this->userRepository->save(entity: $user, flush: true);
                } catch (Exception $e) {
                    return $this->json(
                        data: ['message' => 'Error when trying to update expense. User balance cannot be updated.'],
                        status: Response::HTTP_BAD_REQUEST
                    );
                }
            }
        }

        if (array_key_exists('description', $data)) {
            $expense->setDescription($data['description']);
        }

        if (array_key_exists('category_id', $data)) {
            $expense->setCategory($this->categoryRepository->find($data['category_id']));
        }

        try {
            $this->expenseRepository->save(entity: $expense, flush: true);
        } catch (Exception $e) {
            return $this->json(
                data: ['message' => 'Error when trying to update expense'],
                status: Response::HTTP_BAD_REQUEST);
        }
        return $this->json(
            data: ['message' => 'Successful expense update!'],
            status: Response::HTTP_CREATED
        );
    }
}
