<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // public function create()
    // {
    //     return view('register');
    // }

    public function store(Request $request)
    {

        // dd($request);

        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'blood_group' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // dd($validatedData);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $filename);
        } else {
            $filename = 'default.jpg';
        }

        if($validatedData->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validatedData->errors()
            ],401);
        }


       try {
        // $users = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'address' => $request->address,
        //     'blood_group' => $request->blood_group,
        //     'mobile_number' => $request->mobile_number,
        //     'image' => $filename,
        // ]);

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = $request->password;
        $newUser->address = $request->address;
        $newUser->blood_group = $request->blood_group;
        $newUser->mobile_number = $request->mobile_number;
        $newUser->image = $filename;

        $newUser->save();

        return response()->json([
            'success' => true,
            'message' => 'User Saved Success',
            'data' => $newUser
        ],200);

       } catch (\Error $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ],400);
       }

        // $users->save();

        // auth()->login($user);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'User Created Success',
        //     'data' => $users
        // ],200);

        // return redirect('/home');
    }
}

