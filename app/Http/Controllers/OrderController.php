<?php

namespace App\Http\Controllers;

use App\Services\ContextService;
use App\Services\DTO\OrderRequestData;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrderData(Request $request, SearchService $searchService, ContextService $contextService): ?array
    {
        $token = $request->post('token');
        $command = $request->post('command');

        $data = new OrderRequestData($command, $token);
        $contextId = $contextService->getContextIdByOrder($token);
        return $contextId? $searchService->sendRequest($contextId, $data) : null;
    }

    public function displayOrder(Request $request, SearchService $searchService, ContextService $contextService): RedirectResponse
    {
        $token = $request->post('token');

        $data = new OrderRequestData('DISPLAYORDER', $token);
        $contextId = $contextService->getContextIdByOrder($token);
        if(!$contextId)
        {
            return redirect('/order')->withInput()->withErrors(['error' => 'Заказ не найден. Проверьте корректность токена.']);
        }

        $response = $searchService->sendRequest($contextId, $data);
        return redirect('/order?token='.$token)->with('response', $response);
    }
}
