<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __invoke(BookingRequest $request)
    {
//        $data = [
//            'customer_phone' => $request->post('customer_phone'),
//            'customer_email' => $request->post('customer_email'),
//            'name' => $request->post('name0'),
//            'surname' => $request->post('surname0'),
//            'patronymic' => $request->post('patronymic0'),
//            'gender' => $request->post('gender0'),
//            'birth_date' => $request->post('birth_date0'),
//            'citizenship' => $request->post('citizenship0'),
//            'document_type' => $request->post('document_type0'),
//            'document_number' => $request->post('document_number0'),
//            'document_expire_date' => $request->post('document_expire_date0'),
//            'passenger_phone' => $request->post('passenger_phone0'),
//            'passenger_email' => $request->post('passenger_email0'),
//            'no_email' => $request->post('no_email0')
//        ];
        return json_encode($request->validated());
    }
}
