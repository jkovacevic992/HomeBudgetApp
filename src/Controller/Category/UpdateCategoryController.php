<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCategoryController extends AbstractController
{

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
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

        $category = $this->categoryRepository->find(id: $data['id']);
        $category->setName(name: $data['name']);

        try {
            $this->categoryRepository->save(entity: $category, flush: true);
        } catch (\Exception $e) {
            return $this->json(data: ['message' => 'Error when trying to update category'],
                status: Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            data: ['message' => 'Successful category update!'],
            status: Response::HTTP_CREATED
        );
    }
}
