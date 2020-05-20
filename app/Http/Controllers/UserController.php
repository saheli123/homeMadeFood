<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\PasswordResetSuccess;
use App\Profile;
use App\Contact;
use App\Countries;
use App\States;
use App\Cities;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserCollection;
use App\Http\Resources\CookListCollection;

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
            $contact->update();
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
        //$search=$request->search;
        $cook=$this->getCookList($request)?$this->getCookList($request)->count():0;
        return response($cook, Response::HTTP_CREATED);
    }
    private function getCookList(Request $request){
        $search=$request->search;
        $lng=$request->lng;
        $lat=$request->lat;
        $cook=null;
        if ($search && $search != "") {
          //  $profiles=Profile::search($search)->get()->pluck("user_id")->toArray();
            $cookIds=$this->findNearestCook($lat,$lng);
            if($cookIds){
                $cook = User::whereIn("id", $cookIds);
            }else{

            }
            // $countries=Countries::where("name","like",$search."%")->pluck("id")->toArray();
            // $states=States::where("name","like",$search."%")->pluck("id")->toArray();
            // $cities=Cities::where("name","like",$search."%")->pluck("id")->toArray();
            // $contacts=new Contact();
            // if(count($countries)>0){
            //     $contacts=$contacts->WhereIn('country',$countries);
            // }
            // if(count($states)>0)
            //     $contacts=$contacts->orWhereIn('state',$states);
            // if(count($cities)>0)
            //     $contacts=$contacts->orWhereIn('city',$cities);

            // $contacts=$contacts->pluck("user_id")->toArray();

            //$cook = User::whereIn("id", $contacts);
        } else {
            $cook = new User();
        }
        if($cook){
        $cook=$cook->whereHas("dishes",function ($q) {
            $timezone=Auth::user()?Auth::user()->timezone:"utc";
            $today=\Carbon\Carbon::now()->setTimezone($timezone)->toDateTimeString();
            $q->where('delivery_time', '>=', $today)
            ->orWhere('delivery_end_time', '>=', $today)
                ->orWhereNull('delivery_time');
        });

    }
    return $cook;
    }
    private function findNearestCook($lat,$lng)
{
    // $lat=60.79222220;
    // $lng=-161.75583340;
    $location = DB::table('contacts')
        ->select('user_id', 'latitude', 'longitude', DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $lat,
            $lng
        )))
        ->having('distance', '<',20)
        ->orderBy('distance', 'asc')
        ->pluck('user_id')->toArray();

    return $location;
}
    public function getCooks(Request $request)
    {
        // get the current page
        $search=$request->search;
        $currentPage = $request->get('page') ? $request->get('page') : 1;


        // set the current page
        \Illuminate\Pagination\Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        $cook=$this->getCookList($request);
        //if($currentPage==1)
        if($cook)
            return CookListCollection::collection($cook->simplePaginate(30));
        else return $this->sendError("No cook available");
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
