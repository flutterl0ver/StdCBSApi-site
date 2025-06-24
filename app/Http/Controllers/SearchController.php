<?php

namespace App\Http\Controllers;

use App\Services\DTO\FlightRequestData;
use App\Services\DTO\SearchData;
use App\Services\DTO\SearchResultData;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function getToken(Request $request, SearchService $searchService) : RedirectResponse
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

        $response = $searchService->search('ttbooking', $data);

        if($response['respond']['token'] != '')
        {
            return redirect('/flights?token='.$response['respond']['token']);
        }
        return redirect('/')->withInput();
    }

    public function search(Request $request, SearchService $searchService) : RedirectResponse
    {
        $token = $request->post('token');

        $data = new SearchResultData($token);

        $response = $searchService->search('ttbooking', $data);

        return redirect("/flights?token={$token}")->with([
            'response' => $response
        ]);
    }

    public function getFlightData(Request $request, SearchService $searchService) : array
    {
        $data = json_decode($request->post('data'), true);

        $command = $data['command'];
        $data = new FlightRequestData($command, $data);

        return $searchService->search('ttbooking', $data);
    }
}
