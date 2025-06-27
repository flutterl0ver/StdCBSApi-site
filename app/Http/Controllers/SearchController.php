<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SearchData;
use App\Services\DTO\SearchResultData;
use App\Services\DTO\SelectResultData;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

const CONTEXT_ID = 1;

class SearchController extends Controller
{
    public function search(Request $request, SearchService $searchService) : RedirectResponse
    {
        $rules = [
            'from' => 'required|string',
            'to' => 'required|string',
            'date_to' => 'required|date_format:Y-m-d',
            'date_from' => 'nullable|date_format:Y-m-d',
            'adults' => 'required|integer|min:1',
            'children' => 'required|integer|min:0',
            'infants' => 'required|integer|min:0',
        ];

        $request->validate($rules);

        $hasDateFrom = $request->post('hasDateFrom') == 'true';

        $from = trim($request->post('from'));
        $to = trim($request->post('to'));

        try {
            $p = strpos($from, '(');
            $from_code = substr($from, $p + 1, strlen($from) - $p - 2);
            $from = substr($from, 0, $p);

            $p = strpos($to, '(');
            $to_code = substr($to, $p + 1, strlen($to) - $p - 2);
            $to = substr($to, 0, $p);
        }
        catch (\Exception)
        {
            return redirect('/')->withInput();
        }

        $data = new SearchData(
            $from,
            $from_code,
            $to,
            $to_code,
            $request->post('date_to'),
            $hasDateFrom ? $request->post('date_from') : null,
            $request->post('adults'),
            $request->post('children'),
            $request->post('infants')
        );

        $response = $searchService->search(CONTEXT_ID, $data);

        if(isset($response['respond']) && $response['respond']['token'] != '')
        {
            return redirect('/flights?token='.$response['respond']['token']);
        }
        return redirect('/')->withInput();
    }

    public function searchResult(Request $request, SearchService $searchService) : RedirectResponse
    {
        $token = $request->post('token');

        $data = new SearchResultData($token);

        $response = $searchService->search(CONTEXT_ID, $data);

        return redirect("/flights?token={$token}")->with([
            'response' => $response
        ]);
    }

    public function getFlightData(Request $request, SearchService $searchService) : array
    {
        $data = json_decode($request->post('data'), true);

        $command = $data['command'];
        $data = new FlightRequestData($command, $data);

        return $searchService->search(CONTEXT_ID, $data);
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

        return redirect("/booking?token={$token}")->with([
            'response' => $response
        ]);
    }
}
