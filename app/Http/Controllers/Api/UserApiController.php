<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserApiController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = 0)
    {
        $user = \Auth::user();
        $id = $user->id;
        $data = $request->all();
        $validator = \Validator::make($data, [
            'firstname' => 'max:255',
            'lastname' => 'max:255',
            'mobile' => 'max:20',
            'email' => 'unique:users,email,' . $id . '|email|max:255',
            'gender' => 'in:male,female,other',
            'address' => 'max:100',
            'city' => 'max:100',
            'country' => 'max:100',
            'state' => 'max:100',
            'zipcode' => 'max:16',
            'about' => '',
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
                $user->save();
                return $this->ApiResponse(['message' => 'User updated Successfully'], 200);
            }
        } else {
            $error = $validator->errors()->first();
        }
        return $this->ApiResponse(['error' => $error], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
