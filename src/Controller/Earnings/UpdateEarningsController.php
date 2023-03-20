<?php

namespace App\Controller\Earnings;

use App\Entity\User;
use App\Repository\EarningsRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Validator\EarningsValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateEarningsController extends AbstractController
{
    /**
     * @param EarningsRepositoryInterface $earningsRepository
     * @param UserRepositoryInterface $userRepository
     * @param EarningsValidator $earningsValidator
     */
    public function __construct(
        private readonly EarningsRepositoryInterface $earningsRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EarningsValidator $earningsValidator
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/update/earnings', name: 'app_update_earnings', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        $errors = $this->earningsValidator->validateUpdate($data);
        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }
        /** @var User $user */
        $user = $this->getUser();
        $earnings = $this->earningsRepository->find($data['id']);
        if(!$earnings) {
            return $this->json(
                data: ['message' => 'No earnings with that ID.'],
                status: Response::HTTP_BAD_REQUEST);
        }
        if (array_key_exists(key: 'amount', array: $data)) {
            $diff = $data['amount'] - $earnings->getAmount();
            if ($diff !== 0) {
                $earnings->setAmount(amount: $data['amount']);
                $user->setBalance(balance: $user->getBalance() + $diff);
                try {
                    $this->userRepository->save(entity: $user, flush: true);
                } catch (Exception $e) {
                    return $this->json(
                        data: ['message' => 'Error when trying to update earnings. User balance cannot be updated.'],
                        status: Response::HTTP_BAD_REQUEST
                    );
                }
            }
        }

        if (array_key_exists('description', $data)) {
            $earnings->setDescription($data['description']);
        }

        try {
            $this->earningsRepository->save(entity: $earnings, flush: true);
            return $this->json(
                    data: ['message' => 'Successful earnings update!'],
                    status: Response::HTTP_CREATED
                );
        } catch (Exception $e) {
            return $this->json(
                data: ['message' => 'Error when trying to update earnings'],
                status: Response::HTTP_BAD_REQUEST);
        }
    }
}
