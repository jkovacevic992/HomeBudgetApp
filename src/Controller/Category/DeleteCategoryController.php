<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use App\Validator\CategoryValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteCategoryController extends AbstractController
{

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryValidator $categoryValidator
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly CategoryValidator $categoryValidator
    )
    {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/delete/category', name: 'app_delete_category', methods: ['DELETE'])]
    public function delete(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        $errors = $this->categoryValidator->validateDelete($data);

        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $category  = $this->categoryRepository->find(id: $data['id']);
            if ($category) {
                $this->categoryRepository->remove(entity: $category, flush: true);
                return $this->json(
                    data: ['message' => 'Category deleted successfully!'],
                    status: Response::HTTP_CREATED
                );
            }
        } catch (\Exception $e) {
            return $this->json(
                data: ['message' => 'Could not delete category.'],
                status: Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            data: ['message' => 'No category with that ID.'],
            status: Response::HTTP_BAD_REQUEST);
    }
}
