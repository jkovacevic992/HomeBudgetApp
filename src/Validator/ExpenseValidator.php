<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ExpenseValidator
{
    /**
     * @param ValidatorInterface $validator
     */
    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    /**
     * @param $data
     * @return ConstraintViolationListInterface
     */
    public function validateAdd($data): ConstraintViolationListInterface
    {
        $constraints = new Collection(
            [
                'description' => new NotBlank(['message' => 'Description must not be blank.']),
                'amount' => [
                    new NotBlank(['message' => 'Amount must not be blank.']),
                    new Type(['type' => 'float', 'message' => 'Amount must be a float.']),
                    new Positive(['message' => 'Amount must be a positive number.'])
                    ],
                'category_id' => new Type(['type' => 'int', 'message' => 'Category ID must be an integer.'])


        ]
        );

        $constraints->missingFieldsMessage = 'Check that all the required fields are there.';
        return $this->validator->validate($data, $constraints);
    }

    public function validateUpdate($data)
    {
        $constraints = new Collection([
            'id' => [
                new NotBlank(['message' => 'ID must not be  blank.']),
                new Type(['type' => 'int', 'message' => 'ID must be a number.'])
            ],
            'amount' => [
                new NotBlank(['message' => 'Name must not be  blank.']),
                new Type(['type' => 'float', 'message' => 'Amount must be a float.'])

            ]
        ]);
        if (array_key_exists('description', $data)) {
            $constraints->fields['description'] = new Required([
                new Type(['type' => 'string', 'message' => 'Description must be a string']),
                new NotBlank(['message' => 'Description must not be  blank.'])
            ]);
        }
        $constraints->missingFieldsMessage = 'Missing a required field.';

        return $this->validator->validate($data, $constraints);
    }

    public function validateDelete($data)
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
}
