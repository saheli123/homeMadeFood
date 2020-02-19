<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Http\Requests\ReviewRequest;
use App\FoodItem;
use App\Review;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(FoodItem $product)
    {
       return ReviewResource::collection($product->reviews);

    }

    public function store(ReviewRequest $request , FoodItem $product)
    {
       $review = new Review($request->all());

       $product->reviews()->save($review);

       return response([
         'data' => new ReviewResource($review)
       ],Response::HTTP_CREATED);
    }

    public function update(Request $request, FoodItem $procduct, Review $review)
    {
        $review->update($request->all());
    }

    public function destroy(FoodItem $product, Review $review)
    {
        $review->delete();
        return response(null,Response::HTTP_NO_CONTENT);
    }
}
