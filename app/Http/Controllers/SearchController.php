<?php

namespace App\Http\Controllers;

use App\DTO\FlightRequestData;
use App\DTO\SearchData;
use App\DTO\SearchResultData;
use App\DTO\SelectResultData;
use App\Http\Requests\SearchRequest;
use App\Services\SearchService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

const CONTEXT_ID = 1;

class SearchController extends Controller
{
    public function search(SearchRequest $request, SearchService $searchService)
    {
        $requestData = $request->validated();

        $from = $requestData['from'];
        $to = $requestData['to'];

        $p_from = strpos($from, '(');
        $from_code = substr($from, $p_from + 1, strlen($from) - $p_from - 2);
        $from = substr($from, 0, $p_from);

        $p_to = strpos($to, '(');
        $to_code = substr($to, $p_to + 1, strlen($to) - $p_to - 2);
        $to = substr($to, 0, $p_to);

        $errors = [];
        if (!$p_from || $from_code == '' || $from == '') $errors['from'] = 'Неверный формат ввода.';
        if (!$p_to || $to_code == '' || $to == '') $errors['to'] = 'Неверный формат ввода.';

        if (count($errors) > 0) return redirect('/')->withInput()->withErrors($errors);

        $data = new SearchData(
            $from,
            $from_code,
            $to,
            $to_code,
            $requestData['date_to'],
            $requestData['date_from'] ?? null,
            $requestData['adults'],
            $requestData['children'],
            $requestData['infants']
        );

        try {
            $response = $searchService->search(CONTEXT_ID, $data);
        }
        catch (ConnectionException)
        {
            return redirect('/')->withInput()->withErrors(['other' => 'Ошибка соединения. Проверьте интернет-подключение.']);
        }
        catch (\Throwable $e)
        {
            return redirect('/')->withInput()->withErrors(['other' => 'Что-то пошло не так. Код ошибки: '.$e->getCode()]);
        }

        if (isset($response['respond']) && $response['respond']['token'] != '') {
            return redirect('/flights?token=' . $response['respond']['token']);
        }
        return redirect('/')->withInput();
    }

    public function searchResult(Request $request, SearchService $searchService) : RedirectResponse
    {
        $token = $request->post('token');

        $data = new SearchResultData($token);

        $response = $searchService->searchResult(CONTEXT_ID, $data);
        if (!$response || $response['respond']['token'] == '')
        {
            return redirect('/flights')->withInput()->withErrors(['error' => 'Перелёты не найдены. Проверьте корректность токена.']);
        }

        return redirect("/flights?token={$token}")->with([
            'response' => $response
        ]);
    }

    public function getFlightData(Request $request, SearchService $searchService) : array
    {
        $data = json_decode($request->post('data'), true);

        $command = $data['command'];
        $data = new FlightRequestData($command, $data);

        return $searchService->sendRequest(CONTEXT_ID, $data);
    }

    public function select(Request $request, SearchService $searchService) : array
    {
        $data = json_decode($request->post('data'), true);

        $command = $data['command'];
        unset($data['command']);
        $data = new FlightRequestData($command, $data);

        return $searchService->select(CONTEXT_ID, $data);
    }

    public function selectResult(Request $request, SearchService $searchService) : RedirectResponse
    {
        $token = $request->post('token');

        $data = new SelectResultData($token);

        $response = $searchService->selectResult(CONTEXT_ID, $data);
        if(!$response || $response['respond']['token'] == '')
        {
            return redirect('/booking')->withInput()->withErrors(['error' => 'Перелёт не найден. Проверьте корректность токена.']);
        }

        return redirect("/booking?token={$token}")->withInput()->with([
            'response' => $response
        ]);
    }
}
