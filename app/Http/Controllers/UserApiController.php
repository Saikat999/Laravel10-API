<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Auth;

class UserApiController extends Controller
{
    //Show user
    public function showUser($id=null){
        if($id==''){
            $users=User::get();
            return response()->json(['users'=>$users,200]);
        }else{
            $users = User::find($id);
            return response()->json(['users'=>$users,200]);
        }

    }

    //Add single User
    public function addUser(Request $request){
        
        $data=$request->all();
        // return $data;

        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ];

        $customMessage = [
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'email.unique'=>'Email must be unique',
            'password.required'=>'Password is required',
        ];

        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user-> save();

        $message = 'User Added Successfully';
        return response()->json(['message'=>$message],201);
    }

    //Add multiple users
    public function addMultipleUser(Request $request){
        
        $data=$request->all();
        // return $data;

        $rules = [
            'users.*.name'=>'required',
            'users.*.email'=>'required|email|unique:users',
            'users.*.password'=>'required'
        ];

        $customMessage = [
            'users.*.name.required'=>'Name is required',
            'users.*.email.required'=>'Email is required',
            'users.*.email.email'=>'Email must be a valid email',
            // 'users.*.email.unique'=>'Email must be unique',
            'users.*.password.required'=>'Password is required',
        ];

        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

       foreach($data['users']as $addUser){
        $user = new User();
        $user->name = $addUser['name'];
        $user->email = $addUser['email'];
        $user->password = bcrypt($addUser['password']);
        $user-> save();

        $message = ' Added Multiple Users';
       }
       
       return response()->json(['message'=>$message],201);
    }

    //Update User details
    public function updateUserDetails(Request $request,$id){
        
        $data=$request->all();
        // return $data;

        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ];

        $customMessage = [
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'password.required'=>'Password is required',
        ];

        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = User::findOrFail($id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user-> save();

        $message = 'User Updated Successfully';
        return response()->json(['message'=>$message],202);
    }

    //Delete single user
    public function deleteUser($id){

        User::findOrFail($id)->delete();

        $message = 'User Deleted';
        return response()->json(['message'=>$message],200);
    }

    //Delete single user with json
    public function deleteUserJson(Request $request){

        $data = $request->all();
        User::where('id',$data['id'])->delete();

        $message = 'User Deleted';
        return response()->json(['message'=>$message],200);
    }

    
    //Delete multiple user
    public function deleteMultipleUser($ids){
        $ids = explode(',',$ids);
        User::whereIn('id',$ids)->delete();

        $message = 'Multiple User Deleted';
        return response()->json(['message'=>$message],200);
    }

    //Delete multiple user with json and add JWT
    public function deleteMultipleUserJson(Request $request){

        $header = $request->header('Authorization');
        if($header == ''){
            $message = 'Authorization is required';
            return response()->json(['message'=>$message],422);
        }else{
            if($header == 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IlNhaWthdCBIYXNhbiIsImlhdCI6MTUxNjIzOTAyMn0._5JRAcs_GPRxJhO1B3R22ElmJIQ0FOx7I1lCxpeHV84'){
                
                $data = $request->all();
                User::whereIn('id',$data['ids'])->delete();
        
                $message = 'Multiple User Deleted';
                return response()->json(['message'=>$message],200);

            }else{
                $message = 'Authorization does not match';
                return response()->json(['message'=>$message],422);
            }
        }
       
    }


// Register Api using passport
    public function registerUserUsingPassport(Request $request){

        $data=$request->all();
        // return $data;

        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ];

        $customMessage = [
            'name.required'=>'Name is required',
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'password.required'=>'Password is required',
        ];

        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user-> save();

        if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update(['access_token'=>$access_token]);

            $message = 'User Successfully Registered';
            return response()->json(['message'=>$message,'access_token'=>$access_token],201);
        }else{
            $message = 'Opps! something went wrong';
            return response()->json(['message'=>$message],422);
        } 

    }

    public function loginUserUsingPassport(Request $request){
        $data=$request->all();
        // return $data;

        $rules = [
            'email'=>'required|email|exists:users',
            'password'=>'required'
        ];

        $customMessage = [
            'email.required'=>'Email is required',
            'email.email'=>'Email must be a valid email',
            'email.exists'=>'Email does not exists',
            'password.required'=>'Password is required',
        ];

        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update(['access_token'=>$access_token]);

            $message = 'User Successfully Login';
            return response()->json(['message'=>$message,'access_token'=>$access_token],201);
        }else{
            $message = 'Invalid email or password';
            return response()->json(['message'=>$message],422);
        } 

    }



}
