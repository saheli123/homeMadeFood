<?php

namespace App\Http\Controllers\API;

use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class RegisterController extends BaseController
{
    //
    use VerifiesEmails;
    public $successStatus = 200;
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['slug'] = get_unique_user_slug($request->name);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Phorons')->accessToken;
        $success['name'] =  $user->name;
        //save contact details

        $contact = new \App\Contact();
        $contact->user_id = $user->id;

        $contact->country = $request->get("country");
        $contact->state = $request->get("state");
        $contact->city = $request->get("city") && $request->get("city") != "other" ? $request->get("city") : ($request->get("cityName") ? $request->get("cityName") : "");
        $contact->pincode = $request->get("pincode");
        $contact->phone = $request->get("phone") ? $request->get("phone") : 'Not provided';
        $contact->address_line_1 = "Not provided";
        $contact->save();
        $user->sendApiEmailVerificationNotification();
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authenticated_user = Auth::user();
            $user = User::find($authenticated_user->id);
            if ($user->email_verified_at !== NULL) {


                $tokenResult = $user->createToken('Phorons');
                $token = $tokenResult->token;
                if ($request->remember_me)
                    // $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();
                return response()->json([
                    'access_token' => $tokenResult->accessToken,
                    'token_type' => 'Bearer',
                    // 'expires_at' => Carbon::parse(
                    //     $tokenResult->token->expires_at
                    // )->toDateTimeString()
                ]);
            }else{
                return $this->sendError('Please Verify Email.', ['error' => 'Please Verify Email']);
            }
        } else {
            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response(new UserResource($request->user(),true), Response::HTTP_CREATED);
        //  return response()->json($request->user());
    }
}
