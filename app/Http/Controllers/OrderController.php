<?php

namespace App\Http\Controllers;

use App\DTO\OrderRequestData;
use App\Services\BookingService;
use App\Services\ContextService;
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
        return $contextId ? $searchService->sendRequest($contextId, $data) : null;
    }

    public function displayOrder(Request $request, BookingService $bookingService): RedirectResponse
    {
        $token = $request->post('token');

        $response = $bookingService->displayOrder($token);

        if(!$response || $response['respond']['token'] == '')
        {
            return redirect('/order')->withInput()->withErrors(['error' => 'Заказ не найден. Проверьте корректность токена.']);
        }
        return redirect('/order?token='.$token)->with('response', $response);
    }
}
