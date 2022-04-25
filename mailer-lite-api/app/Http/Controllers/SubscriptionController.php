<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interface\SubscriberProvider;

class SubscriptionController extends Controller
{
    protected $subscriberProvider;
    public function __construct(SubscriberProvider $_subscriberProvider)
    {
        $this->middleware('verified');
        $this->subscriberProvider = $_subscriberProvider;
    }

    public function addSubscriber(Request $request)
    {
        $response = "";
        try {
            $response = $this->subscriberProvider->add_subscriber($request->input('email'), $request->input('name'));
            return response($response['responseMessage'], $response['responseCode']);
        } catch (\Throwable $th) {
            $response = "An Error Occurred While Trying To Get Stream Data, Please Contact Admin";
            return response($response, 500);
        }
    }

    public function addSubscriberField(Request $request)
    {
        $response = "";
        try {
            $response = $this->subscriberProvider->add_subscriber_field($request->input('email'), $request->input('title'), $request->input('type'));
            return response($response['responseMessage'], $response['responseCode']);
        } catch (\Throwable $th) {
            $response = "An Error Occurred While Trying To Get Stream Data, Please Contact Admin";
            return response($response, 500);
        }
    }

    public function getSubscribersField(Request $request)
    {
        $response = "";
        try {
        $page_index = (int) $request->input('pageindex');
        $page_size = (int) $request->input('pagesize');
        $response=  $this->subscriberProvider->get_subscribers_field($request->input('email'), $page_index, $page_size);
        return response($response['responseMessage'], $response['responseCode']);
        } catch (\Throwable $th) {
            $response = "An Error Occurred While Trying To Get Stream Data, Please Contact Admin";
            return response($response, 500);
        }
    }
}
