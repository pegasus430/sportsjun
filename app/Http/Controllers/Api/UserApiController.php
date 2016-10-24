<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Model\Photo;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserApiController extends BaseApiController
{
    public function index(){
        $users = User::select(['id','name'])->paginate(20);
        return $this->ApiResponse($users);
    }

    public function show($id){
        $user = \Auth::user();
        if ($id == $user->id) {
            $user = array_only($user->toArray(),['id','name','firstname','lastname','email','dob','gender','location','address','city_id','city','state_id','state',
                'country_id','country','zip','contact_number','about','newsletter','logoImage']);
        } else
            $user = User::where('id', $id)->select(['id', 'name', 'firstname', 'lastname'])->first();
        return $this->ApiResponse($user);
    }


    public function update(Request $request, $id = 0)
    {
        $user = \Auth::user();
        $id = $user->id;
        $data = $request->all();
        $validator = \Validator::make($data, [
            'firstname' => 'max:255',
            'lastname' => 'max:255',
            'mobile' => 'max:20|numeric',
            'email' => 'unique:users,email,' . $id . '|email|max:255',
            'gender' => 'in:male,female,other',
            'address' => 'max:100',
            'city' => 'max:100',
            'country' => 'max:100',
            'state' => 'max:100',
            'zipcode' => 'max:16',
            'about' => '',
            'image' => 'mimes:png,gif,jpeg,jpg',
        ]);
        $map = [
            'firstname' => 'firstname',
            'lastname' => 'lastname',
            'mobile' => 'contact_number',
            'email' => 'email',
            'gender' => 'gender',
            'address' => 'address',
            'city' => 'city',//?update city_id
            'country' => 'country', //?update country_id
            'state' => 'state', // ?update state_id
            'zipcode' => 'zip',
            'about' => 'about',
        ];

        if (!$validator->fails()) {
            if (!($id === $user->id)) {
                $error = 'Can update only self';
            } else {
                foreach ($map as $key => $value){
                    if(!is_array($value)){
                        if (isset($data[$key])) {
                            $user->$value = $data[$key];
                         }
                    }
                }

                $logo = $request->file('image');
                if($logo) {
                    $albumID = 1;
                    $coverPic = 1;
                    $image_id = Helper::uploadImage($logo, config('constants.PHOTO_PATH.USERS_PROFILE'), $id,
                        $albumID, $coverPic, config('constants.PHOTO.USER_PHOTO'), $id);

                    $logo=Photo::select('url')->where('imageable_type',config('constants.PHOTO.USER_PHOTO'))
                                                ->where('imageable_id',  $id )
                                                ->where('user_id', $id)
                                                ->where('is_album_cover',1)
                                                ->where('id',$image_id)
                                                ->first();
                    if ($logo){
                            $user->logo = $logo->url;
                    }
                }

                $user->save();
                return $this->ApiResponse(['message' => 'User updated Successfully'], 200);
            }
        } else {
            $error = $validator->errors()->first();
        }
        return $this->ApiResponse(['error' => $error], 500);
    }


}
