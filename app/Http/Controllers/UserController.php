<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Profile;
use App\Contact;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    //
    public function index(Request $request){

        return UserCollection::collection(User::all());
    }
    public function GetProfileData($user_id=0){
        $user=User::find($user_id);
        return response([

            'data' => new UserResource($user)

        ], Response::HTTP_CREATED);
    }
    public function updateContact(Request $request){
        try{
            $contact=User::find($request->get("user_id"))->contact;
            if(!$contact){
                $contact=new Contact();
                $contact->user_id=$request->get("user_id");
            }

            $contact->country=$request->get("country");
            $contact->state=$request->get("state");
            $contact->city=$request->get("city") && $request->get("city")!="other"?$request->get("city"):($request->get("cityName")?$request->get("cityName"):"");
            $contact->pincode=$request->get("pincode");
            $contact->phone=$request->get("phone")?$request->get("phone"):'Not provided';
            $contact->address_line_1=$request->get("address_line_1")?$request->get("address_line_1"):'Not provided';
            $contact->save();
            return response([

                'data' => "successfully updated"

            ], Response::HTTP_CREATED);
        }catch(\Exception $e){
            return response([

                'data' => $e->getMessage()

            ], Response::HTTP_CREATED);
        }

    }
    public function getCooks($search = '')
    {
        if ($search != "") {
            $cook = User::where('name', "like", $search . "%")->has("dishes");
        } else {
            $cook = User::has("dishes");
        }
        return UserCollection::collection($cook->paginate(10));
        //return response(new UserCollection($cook), Response::HTTP_CREATED);
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
    public function setMarkAsReadNotification(Request $request){
        $notification = User::find($request->user_id)->notifications()->find($request->notification_id);
        if($notification) {
            $notification->markAsRead();
        }
        return response([

            'data' =>"marked as read"

        ], Response::HTTP_CREATED);
    }
}
