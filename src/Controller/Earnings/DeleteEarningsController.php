<?php

namespace App\Controller\Earnings;

use App\Entity\User;
use App\Repository\EarningsRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteEarningsController extends AbstractController
{

    /**
     * @param EarningsRepositoryInterface $earningsRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly EarningsRepositoryInterface $earningsRepository,
        private readonly UserRepositoryInterface $userRepository
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/delete/earnings', name: 'app_delete_earnings', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        /** @var User $user */
        $user = $this->getUser();
        try {
            $earnings  = $this->earningsRepository->find(id: $data['id']);
            if ($earnings) {
                $this->earningsRepository->remove(entity: $earnings, flush: true);
                $user->setBalance(balance: $user->getBalance() - $earnings->getAmount());
                $this->userRepository->save(entity: $user, flush: true);
                return $this->json(
                    data: ['message' => 'Earnings deleted successfully!'],
                    status: Response::HTTP_CREATED
                );
            }
        } catch (\Exception $e) {
            return $this->json(data: ['message' => 'Could not delete earnings.'],
                status: Response::HTTP_BAD_REQUEST);
        }

        return $this->json(data: ['message' => 'No earnings with that ID.'],
            status: Response::HTTP_BAD_REQUEST);
    }
}
