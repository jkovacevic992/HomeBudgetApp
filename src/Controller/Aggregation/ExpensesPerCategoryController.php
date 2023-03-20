<?php

namespace App\Controller\Aggregation;

use App\Service\DataAggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpensesPerCategoryController extends AbstractController
{
    /**
     * @param DataAggregationService $dataAggregationService
     */
    public function __construct(private readonly DataAggregationService $dataAggregationService)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/category-expenses', name: 'app_expenses_per_category', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $period = null;
        if (array_key_exists('period', $requestData)) {
            if ($requestData['period'] !== 'month' && $requestData['period'] !== 'year') {
                return $this->json(data: 'Period can only be month or year.', status: Response::HTTP_BAD_REQUEST);
            }
            $period = $requestData['period'];
        }
        $data = $this->dataAggregationService->getExpensesPerCategory($requestData, $period);
        return $this->json(data: $data);
    }
}
