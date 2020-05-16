<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PasswordResetSuccess;
use App\Profile;
use App\Contact;
use App\Countries;
use App\States;
use App\Cities;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
class UserController extends BaseController
{
    //

    public function index(Request $request){

        return UserCollection::collection(User::all());
    }
    public function GetProfileDataBySlug($slug=""){
        $user=User::where("slug",$slug)->first();

        return response(

           new UserResource($user)

        , Response::HTTP_CREATED);
    }
    public function GetProfileData($user_id=0){
        $user=User::find($user_id);

        return response([

            'data' => new UserResource($user)

        ], Response::HTTP_CREATED);
    }
    public function updatePassword(Request $request){
        try{

            $current_password=$request->get("current_password");
            $user=User::find($request->get("user_id"));
            //dd($user);
            if(Hash::check($current_password,$user->password)){
                $user->password=Hash::make($request->get("password"));
                $user->save();
                $user->notify(new PasswordResetSuccess());
                return response()->json('Password updated successfully');
            }else{
                return $this->sendError('Wrong password');
            }


        }catch(\Exception $e){
            return $this->sendError('Wrong Password');
        }
    }
    public function updateProfile(Request $request){
        try{
            $user=User::find($request->get("user_id"));
            $user->name=$request->get("name");
            $user->slug= get_unique_user_slug($user->name);
            $user->save();
            $profile=$user->profile;
            if(!$profile){
                $profile=new Profile();
                $profile->user_id=$request->get("user_id");
            }


            $profile->bio=$request->get("bio");
            $profile->dish_type=$request->get("dish_type");

            $profile->save();
            return response([

                'data' => "successfully updated"

            ], Response::HTTP_CREATED);
        }catch(\Exception $e){
            return response([

                'data' => $e->getMessage()

            ], Response::HTTP_CREATED);
        }

    }
    public function updateContact(Request $request){
        try{
            $user=User::find($request->get("user_id"));
            if($request->get("timezone")){
                $user->timezone=$request->get("timezone");
                $user->save();
            }
            $contact=$user->contact;
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
    public function getTotalCook(Request $request){
        $search=$request->post('search');
        $cook=$this->getCookList($search);
        return response($cook->count(), Response::HTTP_CREATED);
    }
    private function getCookList($search = ''){

        if ($search && $search != "") {
            $profiles=Profile::search($search)->get()->pluck("user_id")->toArray();

            $countries=Countries::search($search)->get()->pluck("id")->toArray();
            $states=States::search($search)->get()->pluck("id")->toArray();
            $cities=Cities::search($search)->get()->pluck("id")->toArray();

            $contacts=Contact::WhereIn('country',$countries)->orWhereIn('state',$states)->orWhereIn('city',$cities)->get()->pluck("user_id")->toArray();
            $userarr=User::search($search)->get()->pluck("id")->toArray();

            $users=array_merge($userarr,$profiles,$contacts);
            $cook = User::whereIn("id",$users)->has("dishes");
        } else {
            $cook = User::has("dishes");
        }
        return $cook;
    }
    public function getCooks(Request $request)
    {
        // get the current page
        $search=$request->post('search');
        $currentPage = $request->get('page') ? $request->get('page') : 1;

        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $cook=$this->getCookList($search);
        //if($currentPage==1)
        return UserCollection::collection($cook->simplePaginate(30));
        //return response(new UserCollection($cook), Response::HTTP_CREATED);
        //return response()->json($cook);
    }
    public function getCookById($id = 0)
    {
        $cook = [];
        if ($id != 0) {
            $cook = User::find($id);
        }

        return response()->json(new UserResource($cook));
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
