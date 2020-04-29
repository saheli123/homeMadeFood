<?php

namespace App\Http\Controllers;

use App\DishGallery;
use Intervention\Image\Facades\Image;
use App\Http\Requests\FoodItemRequest;
use App\Http\Resources\FoodItemCollection;
use App\Http\Resources\FoodItemResource;
use App\FoodItem;
use App\Http\Controllers\API\BaseController;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request as Input;

class FoodItemController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')->except('index', 'show', 'showDish', 'searchFood', 'getDishes');
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
        $today = date("Y-m-d H:m:s");
        $query = FoodItem::where('user_id', $cookId);
        if (Auth::id() != $cookId) {
            $query->where(
                function ($q) use ($today) {
                    $q->where('delivery_time', '>=', $today)
                        ->orWhere('delivery_end_time', '>=', $today)
                        ->orWhereNull('delivery_time');
                }
            );
        }
        return FoodItemCollection::collection($query->orderBy('updated_at', 'desc')->simplePaginate(5));
    }

    public function store(FoodItemRequest $request)
    {
        if (empty($request->id)) {
            $product = new FoodItem;
            $product->user_id = $request->user_id;
        } else {
            $product = FoodItem::find($request->id);
            // dd($product->user_id);
            if ($product->user_id != $request->user_id) {
                return $this->sendError("Sorry wrong dish");
            }
        }

        //,'delivery_time','picture', 'detail','slug', 'dish_type','cuisine_type','price','user_id'
        $product->name = $request->name;
        $product->slug = get_unique_dish_slug($request->name);

        $product->detail = $request->details;
        $product->price = $request->price;
        $product->unit = $request->unit;
        $product->picture = "img/food_default.jpg";

        $product->dish_type = $request->dish_type;
        $product->cuisine_type = $request->cuisine_type;
        $product->delivery_time = $request->delivery_time && $request->delivery_time !== 'Invalid date' ? $request->delivery_time : null;
        $product->delivery_end_time = $request->delivery_end_time && $request->delivery_end_time !== 'Invalid date' ? $request->delivery_end_time : null;
        $product->delivery_type = $request->delivery_type;


        $product->save();
        if ($request->delivery_type != "Delivery") {


            $contact = \App\User::find($request->get("user_id"))->contact;
            if (!$contact) {
                $contact = new \App\Contact();
                $contact->user_id = $request->get("user_id");
            }

            $contact->country = $request->get("country");
            $contact->state = $request->get("state");
            $contact->city = $request->get("city") && $request->get("city") != "other" ? $request->get("city") : ($request->get("cityName") ? $request->get("cityName") : "");
            $contact->pincode = $request->get("pincode");
            $contact->phone = $request->get("phone") ? $request->get("phone") : 'Not provided';
            $contact->address_line_1 = $request->get("address_line_1") ? $request->get("address_line_1") : 'Not provided';
            $contact->save();
        }

        return response([

            'data' => new FoodItemResource($product),
            'totalDish' => \App\User::find($request->get("user_id"))->dishes->count()

        ], Response::HTTP_CREATED);
    }
    public function deleteDishPhoto(Request $request)
    {
        try {


            $imgId = $request->imageId;
            DishGallery::find($imgId)->delete();
            return response([
                'success' => 'Deleted successfully',


            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response([
                'error' => 'Not deleted',


            ], Response::HTTP_CREATED);
        }
    }
    public function uploadDishPicture(Request $request)
    {
        try {
            $images = $request->file("files");
            $dish_id = $request->get('dishId');
            foreach ($images as $key => $image) {
                $date = strtotime(now());
                $fileName = $date . $key . ".jpg";
                $fileName = 'media/dish_' . $dish_id . "_" . $fileName;
                $img = Image::make($image)->save(public_path('img/') . $fileName);

                if ($img) {
                    $imageStore = new \App\DishGallery();
                    $imageStore->dish_id = $dish_id;
                    $imageStore->image = 'img/' . $fileName;
                    $imageStore->save();
                }
            }

            return response([
                'success' => 'Uploaded successfully',
                'data' => FoodItem::find($dish_id)->images

            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage()

            ], Response::HTTP_CREATED);
        }
    }

    public function showDish($dishId)
    {
        return response([

            new FoodItemResource(FoodItem::find($dishId))

        ], Response::HTTP_CREATED);
    }
    public function show(Request $request, $cookId)
    {
        // return new FoodItemResource($product);
        // get the current page
        $currentPage = $request->get('page') ? $request->get('page') : 1;
        $profileId = $request->get('profileId') ? $request->get('profileId') : 0;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
        if ($profileId != 0)
            $today = \Carbon\Carbon::now()->setTimezone(\App\User::find($profileId)->timezone)->toDateTimeString();
        else {
            $today = \Carbon\Carbon::now()->setTimezone(\App\User::find($cookId)->timezone)->toDateTimeString();
        }
        $query = FoodItem::where('user_id', $cookId);

        if ($profileId != $cookId) {
            $query->where(
                function ($q) use ($today) {
                    $q->where('delivery_time', '>=', $today)
                        ->orWhere('delivery_end_time', '>=', $today)
                        ->orWhereNull('delivery_time');
                }
            );
        }


        return FoodItemCollection::collection($query->orderBy('updated_at', 'desc')->simplePaginate(5));
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
