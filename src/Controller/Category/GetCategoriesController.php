<?php

namespace App\Controller\Category;

use App\Repository\CategoryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetCategoriesController extends AbstractController
{
    public function __construct(private CategoryRepositoryInterface $categoryRepository)
    {
    }

    #[Route('/api/categories', name: 'app_get_categories', methods: ['GET'])]
    public function get(): JsonResponse
    {
        $data = $this->categoryRepository->findAll();
        return $this->json(data: $data);
    }
}
