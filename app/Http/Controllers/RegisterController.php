<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function store(Request $request)
    {

        $validatedData = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:8',
            'address' => 'required|string|max:255',
            'blood_group' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

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

        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
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
    }
}

