<?php

namespace App\Controller\User;

use App\Factory\UserFactory;
use App\Validator\UserValidator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api')]
class RegisterController extends AbstractController
{

    /**
     * @param ManagerRegistry $managerRegistry
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserValidator $userValidator
     */
    public function __construct(
        private readonly ManagerRegistry    $managerRegistry,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserValidator $userValidator
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
        $errors = $this->userValidator->validateUserRequest($data);

        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $user = UserFactory::create();

        $user->setEmail($data['email']);
        $hashedPassword = $this->passwordHasher->hashPassword(
            user: $user,
            plainPassword: $data['password']
        );
        $user->setPassword($hashedPassword);
        try {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            return $this->json(
                data: ['message' => 'Could not register new user.'],
                status: Response::HTTP_BAD_REQUEST);
        }


        return $this->json(data: ['message' => 'Registered Successfully'], status: Response::HTTP_CREATED);
    }
}
