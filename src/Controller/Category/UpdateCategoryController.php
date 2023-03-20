<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use App\Validator\CategoryValidator;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCategoryController extends AbstractController
{

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryValidator $categoryValidator
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly CategoryValidator $categoryValidator
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/update/category', name: 'app_update_category', methods: ['PUT'])]
    public function update(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        $errors = $this->categoryValidator->validateUpdate($data);
        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $category = $this->categoryRepository->find(id: $data['id']);
            if ($category) {
                $category->setName(name: $data['name']);
                $this->categoryRepository->save(entity: $category, flush: true);
                return $this->json(
                    data: ['message' => 'Successful category update!'],
                    status: Response::HTTP_CREATED
                );
            }
            return $this->json(data: ['message' => 'Could not find category.'],
                status: Response::HTTP_BAD_REQUEST);
        } catch (Exception $e) {
            return $this->json(data: ['message' => 'Error when trying to update category'],
                status: Response::HTTP_BAD_REQUEST);
        }
    }
}
