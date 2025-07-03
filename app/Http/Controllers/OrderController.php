<?php

namespace App\Http\Controllers;

use App\Services\ContextService;
use App\Services\DTO\OrderRequestData;
use App\Services\SearchService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrderData(Request $request, SearchService $searchService, ContextService $contextService): array
    {
        $token = $request->post('token');
        $command = $request->post('command');

        $data = new OrderRequestData($command, $token);
        return $searchService->search($contextService->getContextIdByOrder($token), $data);
    }

    public function displayOrder(Request $request, SearchService $searchService, ContextService $contextService): array
    {
        $token = $request->post('token');

        $data = new OrderRequestData('DISPLAYORDER', $token);
        return $searchService->search($contextService->getContextIdByOrder($token), $data);
    }
}
