<?php

namespace App\Http\Controllers;

use App\Http\Requests\FoodItemRequest;
use App\Http\Resources\FoodItemCollection;
use App\Http\Resources\FoodItemResource;
use App\FoodItem;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Input;

class FoodItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'searchFood', 'getDishes');
    }

    public function index()
    {
        return FoodItemCollection::collection(FoodItem::paginate(5));
    }

    public function searchFood($food = '')
    {
        return FoodItemCollection::collection(FoodItem::where('name', 'like', $food . '%')->paginate(5));
    }

    public function getDishes(Request $request, $cookId = '')
    {
        // get the current page
        $currentPage = $request->get('page') ? $request->get('page') : 1;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        return FoodItemCollection::collection(FoodItem::where('user_id', $cookId)->simplePaginate(5));
    }

    public function store(FoodItemRequest $request)
    {
        $product = new FoodItem;
        //,'delivery_time','picture', 'detail','slug', 'dish_type','cuisine_type','price','user_id'
        $product->name = $request->name;
        $product->slug = get_unique_dish_slug($request->name);

        $product->detail = $request->details;
        $product->price = $request->price;
        $product->picture="img/food_default.jpg";

        $product->dish_type = $request->dish_type;
        $product->cuisine_type = $request->cuisine_type;
        $product->delivery_time = $request->delivery_time;
        $product->user_id = $request->user_id;

        $product->save();

        return response([

            'data' => new FoodItemResource($product)

        ], Response::HTTP_CREATED);
    }

    public function show(Request $request,$cookId)
    {
       // return new FoodItemResource($product);
        // get the current page
        $currentPage = $request->get('page') ? $request->get('page') : 1;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        return FoodItemCollection::collection(FoodItem::where('user_id', $cookId)->simplePaginate(5));
    }

    public function update(Request $request, FoodItem $product)
    {
        $this->userAuthorize($product);

        $request['detail'] = $request->description;

        unset($request['description']);

        $product->update($request->all());

        return response([

            'data' => new FoodItemResource($product)

        ], Response::HTTP_CREATED);
    }

    public function destroy(FoodItem $product)
    {
        $product->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function userAuthorize($product)
    {
        if (Auth::user()->id != $product->user_id) {
            //throw your exception text here;

        }
    }
}
