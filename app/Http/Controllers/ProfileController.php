<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Input;
use Symfony\Component\HttpFoundation\Response;
use App\Profile;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        return response()->json($user->profile);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    public function uploadProfilePicture(Request $request)
    {
        try {
            $image = $request->file("file");
            $user_id = $request->get('user_id');
            $fileName = $request->get("file_name");
            $fileName = 'media/profile_' . $user_id . "_" . $fileName;
            $img = Image::make($image)->save(public_path('img/') . $fileName);

            if ($img) {
                $profileStore = User::find($user_id)->profile;
                if ($profileStore) {
                    $profileStore->image = 'img/' . $fileName;
                    $profileStore->save();
                } else {
                    $profile = new Profile();
                    $profile->user_id = $user_id;
                    $profile->image = 'img/' . $fileName;
                    $profile->save();
                }
            }
            return response([
                'success' => 'Uploaded successfully',
                'data' => url('img/'.$fileName)

            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage()

            ], Response::HTTP_CREATED);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
