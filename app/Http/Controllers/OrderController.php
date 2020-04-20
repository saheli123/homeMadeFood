<?php

namespace App\Http\Controllers;

use App\Events\CheckOutEvent;
use App\order;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Support\Facades\DB;
use App\OrderProduct;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderedDishesResource;
use App\Notifications\Checkout;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }
    public function getOrderDetailsByOrderId(Request $request, $orderId = 0)
    {
        return OrderedDishesResource::collection(OrderProduct::where("order_id", $orderId)->get());
    }
    public function getOrdersByCustomer(Request $request, $userId = 0)
    {

        // get the current page
        $currentPage = $request->get('page') ? $request->get('page') : 1;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        return OrderCollection::collection(Order::where('customer_id', $userId)->orderBy("updated_at", "desc")->simplePaginate(5));
    }
    public function totalOrders($type = "cook", $userId = 0)
    {
        if ($type == "cook") {
            $orders = User::find($userId)->ordersbycustomer->groupBy("order_id")->count();
        }else{
            $orders=Order::where('customer_id', $userId)->count();
        }
        return response($orders, Response::HTTP_CREATED);
    }
    public function getOrdersByCook(Request $request, $userId = 0)
    {

        // get the current page
        $currentPage = $request->get('page') ? $request->get('page') : 1;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        $orders = User::find($userId)->ordersbycustomer->pluck("order_id")->toArray();


        return OrderCollection::collection(Order::whereIn("id", $orders)->orderBy("updated_at", "desc")->simplePaginate(5));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        DB::beginTransaction();
        try {
            $customer_id = $request->userId;
            $order = Order::Create([
                'customer_id' => $customer_id,
                'billing_address' => $request->address,
                'billing_country' => $request->country ? $request->country : User::find($customer_id)->contact->country,
                'billing_city' => $request->city ? $request->city : User::find($customer_id)->contact->city,
                'billing_state' => $request->state ? $request->state : User::find($customer_id)->contact->state,
                'billing_pincode' => $request->pincode ? $request->pincode : User::find($customer_id)->contact->pincode,
                'billing_phone' => $request->phone,
                'billing_total' => \Cart::session($request->userId)->getTotal()
            ]);
            $cookId = 0;
            //dd($order);
            \Cart::session($request->userId)->getContent()->each(function ($item) use (&$order, &$cookId) {
                if ($cookId == 0) {
                    $cookId = $item->associatedModel->user_id;
                }

                OrderProduct::Create([
                    'order_id' => $order->id,
                    'product_id' => $item->associatedModel->id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            });
            if (!\Cart::session($request->userId)->isEmpty()) {
                \Cart::session($request->userId)->clear();
            }
            $cook = User::find($cookId);
            $customer = User::find($request->userId);
            DB::commit();
            $cook->notify(new Checkout($customer, $order));
            //  event(new CheckOutEvent(User::find($request->userId),$order));
            return response([

                'data' => "Order haved been placed successfully. " . User::find($cookId)->name . " will contact you as soon as possible."

            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response([
                'data' => $e->getMessage()
            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(order $order)
    {
        //
    }
}
