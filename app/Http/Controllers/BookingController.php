<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;

class BookingController extends Controller
{
    public function __invoke(BookingRequest $request)
    {
        $requestData = $request->validated();
        return json_encode($request->validated());
    }
}
