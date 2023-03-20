<?php

namespace App\Controller\Earnings;

use App\Entity\User;
use App\Factory\EarningsFactory;
use App\Repository\EarningsRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Validator\EarningsValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddEarningsController extends AbstractController
{
    /**
     * @param EarningsRepositoryInterface $earningsRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly EarningsRepositoryInterface $earningsRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly EarningsValidator $validator
    ) {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/add/earnings', name: 'app_add_earnings', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        /** @var User $user */
        $user = $this->getUser();

        $errors = $this->validator->validateAdd($data);

        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $earnings = EarningsFactory::create();
        $earnings->setAmount(amount: $data['amount']);
        $earnings->setDescription(description: $data['description']);
        $earnings->setUser(user: $user);

        $user->setBalance(balance: $user->getBalance() + $data['amount']);

        try {
            $this->earningsRepository->save(entity: $earnings, flush: true);
            $this->userRepository->save(entity:  $user, flush: true);
        } catch (\Exception $exception) {
            return $this->json(
                data: ['message' => 'Error when trying to add earnings'],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(
            data: ['message' => 'Added new earnings successfully'],
            status: Response::HTTP_CREATED
        );
    }
}
