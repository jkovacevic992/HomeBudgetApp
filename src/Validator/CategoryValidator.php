<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CategoryValidator
{

    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validateAdd(array $data): ConstraintViolationListInterface
    {
        $constraints = new Collection([
            'name' => [
                new NotBlank(['message' => 'Name must not be  blank.']),
                new Type(['type' => 'string', 'message' => 'Name must be a string'])

            ]
        ]);
        return $this->validator->validate($data, $constraints);
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validateDelete(array $data): ConstraintViolationListInterface
    {
        $constraints = new Collection([
            'id' => [
                new NotBlank(['message' => 'ID must not be  blank.']),
                new Type(['type' => 'int', 'message' => 'ID must be a number.'])
            ]
            ]);
        $constraints->missingFieldsMessage = 'Missing a required field.';
        return $this->validator->validate($data, $constraints);
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validateUpdate(array $data): ConstraintViolationListInterface
    {
        $constraints = new Collection([
            'id' => [
                new NotBlank(['message' => 'ID must not be  blank.']),
                new Type(['type' => 'int', 'message' => 'ID must be a number.'])
            ],
            'name' => [
                new NotBlank(['message' => 'Name must not be  blank.']),
                new Type(['type' => 'string', 'message' => 'Name must be a string'])

            ]
        ]);
        $constraints->missingFieldsMessage = 'Missing a required field.';

        return $this->validator->validate($data, $constraints);
    }
}