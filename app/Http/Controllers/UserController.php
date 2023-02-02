<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            //'email'=> 'required|email|exists:users,email',
            'password'=>'required|min:4'
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $user=User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $response = [
            'user' => new UserResource($user),
            'token' => $token
        ];
        return response($response, 201);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'fullname' => 'required',
            //'email'=> 'required|email|unique:users,email',
            'password'=>'required|min:4'
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $user = User::create([
            "fullname"=>$request->fullname,
            //"email"=>$request->email,
            "password"=> Hash::make($request->password),
        ]);

        if(!$user){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }
        return response()->json([
            'message' => 'Succesful',
            'user' => new UserResource($user)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(),[
            'fullname' => 'required',
            // 'email' => [
            //     'required',
            //     Rule::unique('users')->ignore($user->id)
            // ],
            'password'=>'required|min:4'
        ]);

        if($validator->fails()){
            return response()->json([
                'validationMessages' => $validator->errors()
            ], 400);
        }

        $fullname=$request->fullname;
        //$email=$request->email;
        $password=$request->password;

        $result = $user->update([
            "fullname"=>$fullname,
            //"email"=>$email,
            "password"=> Hash::make($request->$password)
        ]);

        if(!$result){
            return response()->json([
                'message' => 'Error: Try Again !'
            ]);
        }
        return response()->json([
            'message' => 'Succesful',
            'staff' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Deleted'
        ]);
    }
}
