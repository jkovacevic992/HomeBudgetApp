<?php

namespace App\Controller\Category;

use App\Factory\CategoryFactory;
use App\Repository\CategoryRepositoryInterface;
use App\Validator\CategoryValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostCategoryController extends AbstractController
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryValidator $categoryValidator
     */
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository, private readonly CategoryValidator $categoryValidator)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/add/category', name: 'app_post_category', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode(json: $request->getContent(), associative: true);

        $errors = $this->categoryValidator->validateAdd($data);

        if (count($errors)) {
            return $this->json(
                data: $errors[0]->getMessage(),
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $category = CategoryFactory::create();
        $category->setName($data['name']);

        try {
            $this->categoryRepository->save(entity: $category, flush: true);
        } catch (\Exception $exception) {
            return $this->json(
                data: ['message' => 'Error when trying to save category'],
                status: Response::HTTP_BAD_REQUEST
            );
        }

        return $this->json(
            data: ['message' => 'Created new category successfully'],
            status: Response::HTTP_CREATED
        );
    }
}
