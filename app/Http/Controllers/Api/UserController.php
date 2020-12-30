<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserTypesResource;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return $request->user();
    }

    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(10));
        } else {
            return UserResource::collection(User::all());
        }
    }

    public function types(){
        return UserTypesResource::collection(User::select('type')->distinct('type')->get());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function patchUser($id, Request $request)
    {
        $user = User::where('id', '=', $id);
        if ($request->has('loggedin')) {
            if ($request->loggedin) {
                $user->update(['logged_at' => now()]);
            } else {
                $user->update(['logged_at' => null]);
            }
        }

        // Available at
        if ($request->has('available')) {
            if ($request->available) {
                $user->update(['available_at' => now()]);
            } else {
                $user->update(['available_at' => null]);
            }
        }
        
        return User::findOrFail($id);
    }

    public function getCurrentOrder($id)
    {
        $order = Order::where('prepared_by', '=', $id)->where('status', '=', 'H')->orWhere('status', '=', 'P')->first();
        if ($order == null) {
            return null;
        }
        return $order->id;
    }


    public function usersByType(string $type_name)
    {
        return UserResource::collection(User::where('type', $type_name)->get());
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        if($request->hasFile('photo_url')){
            $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
            $request->file('photo_url')->storeAs('public/fotos', $generated_new_name);
            $user->photo_url=$generated_new_name;
        }else{
            $user->photo_url='default_avatar.jpg';
        }
        $user->save();
        return new UserResource($user);
    }
    /* SEM StoreUserRequest */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:8|confirmed',
    //         'age' => 'required|integer|min:18|max:75',
    //         'department_id' => 'required|integer',
    //     ]);
    //     $user = new User();
    //     $user->fill($request->all());
    //     $user->password = Hash::make($user->password);
    //     $user->save();
    //     return response()->json(new UserResource($user), 201);
    // }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return new UserResource($user);
    }
    /* SEM UpdateUserRequest */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'name' => 'required|regex:/^[A-Za-záàâãéèêíóôõúçÁÀÂÃÉÈÍÓÔÕÚÇ ]+$/',
    //         'email' => 'required|email|unique:users,email,' . $this->user->id,
    //         'password' => 'nullable|string|min:6|confirmed',
    //         'age' => 'required|integer|min:18|max:75',
    //         'department_id' => 'required|integer',
    //     ]);
    //     $user = User::findOrFail($id);
    //     $user->update($request->all());
    //     return new UserResource($user);
    // }

    public function destroy(User $user)
    {
        $removedUser = $user;
        if($user->type == 'C'){
            $user->customer()->delete();
        }
        $user->delete();
        $user->save();
        return new UserResource($removedUser);
    }
    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();
    //     return response()->json(null, 204);
    // }


    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = User::where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = User::where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }
}
