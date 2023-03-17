<?php

namespace App\Controller;

use App\Factory\CategoryFactory;
use App\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCategoryController extends AbstractController
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
    #[Route('/api/category', name: 'app_post_category', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        $category = CategoryFactory::create();
        $category->setName($data['name']);

        try {
            $this->categoryRepository->save(entity: $category, flush: true);
        } catch (\Exception $exception) {
            return $this->json(
                data: ['message' => 'Error when trying to create category'],
                status: Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            data: ['message' => 'Created new category successfully'],
            status: Response::HTTP_CREATED
        );
    }
}
