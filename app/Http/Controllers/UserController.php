<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    //
    public function getCooks($search = '')
    {
        if ($search != "") {
            $cook = User::with("contact")->where('name', "like", $search . "%")->has("dishes")->withCount('dishes')->get();
        } else {
            $cook = User::with("contact")->has("dishes")->withCount('dishes as dishesCount')->get();
        }

        return response()->json($cook);
    }
    public function getCookById($id = 0)
    {
        $cook = [];
        if ($id != 0) {
            $cook = User::with("contact")->withCount('dishes as dishesCount')->find($id);
        }

        return response()->json($cook);
    }
}
