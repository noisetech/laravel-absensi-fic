<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {

        $users = User::when(request()->search, function ($users) {
            $users = $users->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        $users->appends(['search' => request()->search]);

        return new UserResource(true, 'List Data Users', $users);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'position' => 'required',
            'daprtemen' => 'required',
            'password' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $users = new User();
        $users->name = $request->name;
        $users->email = $request->name;
        $users->dapertemen = $request->dapertemen;
        $users->position = $request->position;
        $users->password = Hash::make($request->password);
        $users->save();

        if ($users) {
            return new UserResource(true, 'Data User Successfully Added!', $users);
        }

        return new UserResource(false, 'Data User Failed to Add!', null);
    }

    public function destroy(User $user){
        
    }
}
