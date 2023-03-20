<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCategoriesController extends AbstractController
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository)
    {
    }

    #[Route('/api/categories', name: 'app_get_categories', methods: ['GET'])]
    public function get(): JsonResponse
    {
        $data = $this->categoryRepository->findAll();
        if ($data) {
            return $this->json(data: $data);
        }
        return $this->json('No categories found.');
    }
}
