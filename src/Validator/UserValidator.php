<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidator
{

    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validateUserRequest(array $data): ConstraintViolationListInterface
    {
        $constraints = new Collection(
            [
                'email' => [
                    new Email(['message' => 'Invalid email.']),
                    new NotBlank(['message'=> 'Email field must not be blank.'])
                ],
                'password' => [
                    new NotBlank(['message'=> 'Password field must not be blank.']),
                    new Type(['type' => 'string', 'message' => 'Password must be a string'])
                ]
            ]
        );
        $constraints->missingFieldsMessage = 'One of the required fields is missing.';

        return $this->validator->validate(value: $data, constraints: $constraints);
    }
}