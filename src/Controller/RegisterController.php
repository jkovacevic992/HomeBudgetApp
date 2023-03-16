<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(public ManagerRegistry $managerRegistry)
    {
    }

    /**
     * Method used to register the user using data from request
     * @param Request $request
     * @return Response
     */
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), associative: true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(password_hash($data['password'], algo: PASSWORD_DEFAULT));

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(data: 'User created', status: Response::HTTP_CREATED);
    }
}
