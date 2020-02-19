<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
class UserController extends Controller
{
    //
    public function getCooks($search=''){
        if($search!=""){
            $cook=User::where('name',"like",$search."%")->get();
        }else{
            $cook=User::get();
        }

        return response()->json($cook);
    }
}
