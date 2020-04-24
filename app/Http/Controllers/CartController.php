<?php

namespace App\Http\Controllers;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
//use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\FoodItem;

class CartController extends Controller
{
    //
    public function getCart($userId){
        // $cart=\Cart::session($userId)->getContent();
        $cartResult=[];
        //dd($cart);
        $totalItemCount=0;
        \Cart::session($userId)->getContent()->each(function($item) use (&$cartResult,&$totalItemCount)
            {
                array_push($cartResult,[
                            'id'=>$item->associatedModel->id,
                            'name'=>$item->name,
                            'price'=>$item->price,
                            'cookId'=>$item->associatedModel->user_id,
                            'amount'=>$item->quantity,
                ]);
                $totalItemCount+=$item->quantity;

            });

        return response(["cartItems"=>$cartResult,"total"=>$totalItemCount], Response::HTTP_CREATED);
    }
    public function addToCart(Request $request)
    {
        $cart = $request->cart;
        if (!empty($cart)) {
            if(!\Cart::session($request->userId)->isEmpty()){
                \Cart::session($request->userId)->clear();
            }
            foreach ($cart as $item) {
              //  dd($item['id']);
                $product = FoodItem::find($item['id']);

                \Cart::session($request->userId)->add(array(
                    'id' => uniqid(),
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['amount'],
                    'attributes' => array(),
                    'associatedModel' => $product
                ));
            }
        }else{
            \Cart::session($request->userId)->clear();
        }
        return response([

            'data' =>  \Cart::session($request->userId)->getContent()

        ], Response::HTTP_CREATED);
    }
}
