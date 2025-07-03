<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Services\BookingService;
use App\Services\DTO\BookingRequestData;
use App\Services\DTO\PassengerData;
use Illuminate\Http\RedirectResponse;

class BookingController extends Controller
{
    public function __invoke(BookingRequest $request, BookingService $bookingService): RedirectResponse | array
    {
        $requestData = $request->validated();

        $passengers = [];
        for($i = 0; $i < $requestData['passengers_count']; $i++)
        {
            $passengers[] = new PassengerData(
                $requestData['name'.$i],
                $requestData['surname'.$i],
                $requestData['patronymic'.$i],
                $requestData['passenger_type'.$i],
                $requestData['gender'.$i],
                $requestData['birth_date'.$i],
                $requestData['citizenship'.$i],
                $requestData['document_type'.$i],
                $requestData['document_number'.$i],
                $requestData['document_expire_date'.$i],
                $requestData['passenger_phone'.$i],
                $requestData['passenger_email'.$i] ?? null,
                $requestData['no_email'.$i] ?? null
            );
        }
        $data = new BookingRequestData(
            $requestData['customer_phone'],
            $requestData['customer_email'],
            $requestData['token'],
            $passengers
        );

        $response = $bookingService->createBooking($data);

        if(!$response || $response['respond']['token'] == '')
        {
            return ['request' => $data->toArray(), 'response' => $response];
        }

        return redirect('/order?token='.$response['respond']['token']);
    }
}
