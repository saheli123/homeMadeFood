<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Profile;
use App\Http\Resources\UserCollection;
class UserController extends Controller
{
    //
    public function index(Request $request){

        return UserCollection::collection(User::all());
    }
    public function getCooks($search = '')
    {
        if ($search != "") {
            $cook = User::where('name', "like", $search . "%")->has("dishes");
        } else {
            $cook = User::has("dishes");
        }
        return UserCollection::collection($cook->paginate(10));
        //return response()->json($cook);
    }
    public function getCookById($id = 0)
    {
        $cook = [];
        if ($id != 0) {
            $cook = User::with("contact","profile")->withCount('dishes as dishesCount')->find($id);
        }

        return response()->json($cook);
    }
}
