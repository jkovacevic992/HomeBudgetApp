<?php

namespace App\Controller\Aggregation;

use App\Service\DataAggregationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinancialSummaryController extends AbstractController
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
    #[Route('/api/summary', name: 'app_financial_summary', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $requestData = $request->query->all();
        $from = null;
        $to = null;
        if(!array_key_exists('from', $requestData) && !array_key_exists('to', $requestData)) {
            return $this->json(
                data: 'Either "from" or "to" required in the request.',
                status: Response::HTTP_BAD_REQUEST
            );
        }
        if (array_key_exists('from', $requestData)) {
            $from = $requestData['from'];
        }
        if (array_key_exists('to', $requestData)) {
            $to = $requestData['to'];
        }

        $finances = $this->dataAggregationService->getExpensesAndEarningsForPeriod(from: $from,to: $to);

        return $this->json(data: $finances);
    }
}
