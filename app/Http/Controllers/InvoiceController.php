<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __invoke(Request $request, BookingService $bookingService): View | RedirectResponse | string
    {
        $token = $request->query('token');
        $passenger = $request->query('passenger');
        if($token == null || $passenger == null) return redirect()->back();

        $response = $bookingService->displayOrder($token);
        if(!$response || $response['respond']['token'] == '') return redirect()->back();

        $reservation = $response['respond']['bookingFile']['reservations']['reservation'][0];
        $tickets = $reservation['products']['airTicket'];
        if($passenger >= count($tickets)) return redirect()->back();

        return view('invoice')->with(['reservation' => $reservation]);
    }
}
