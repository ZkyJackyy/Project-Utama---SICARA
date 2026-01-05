<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    private $phone = '6282384522629';

    public function index()
    {
        return view('customer.customer_service.index');
    }

    public function redirect(Request $request)
    {
        $message = urlencode($request->message);
        return redirect("https://wa.me/{$this->phone}?text={$message}");
    }
}
