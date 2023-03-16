<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{

    /**
     * @param ManagerRegistry $managerRegistry
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(
        public ManagerRegistry $managerRegistry,
        public UserPasswordHasherInterface $passwordHasher
    ){}

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
        $hashedPassword = $this->passwordHasher->hashPassword(
            user: $user,
            plainPassword: $data['password']
        );
        $user->setPassword($hashedPassword);

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json(data: 'User created', status: Response::HTTP_CREATED);
    }
}
