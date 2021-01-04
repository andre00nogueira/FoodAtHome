<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Resources\UserToManagerResource;
use App\Http\Resources\OrderResource as OrderResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserTypesResource;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return $request->user();
    }

    public function index(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        if ($user->can('viewAny', $user)) {
            if ($request->has('page')) {
                return UserResource::collection(User::paginate(10));
            } else {
                return UserResource::collection(User::all());
            }
        }
    }

    public function types()
    {
        return UserTypesResource::collection(User::select('type')->distinct('type')->get());
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        if ($user->can('view', Auth::user())) {
            return new UserResource($user);
        }
        abort(403);
    }

    public function patchUser($id, Request $request)
    {
        $user = User::findOrFail($id);
        $now = now();
        if ($request->has('loggedin')) {
            if ($request->loggedin) {
                $user->logged_at = $now;
            } else {
                $user->logged_at = null;
            }
        }

        // Available at
        if ($request->has('available')) {
            if ($request->available) {
                $user->available_at = $now;
            } else {
                $user->available_at = null;
            }
        }

        if ($request->has('photo_url')) {
            if ($request->photo_url == 'default_avatar.jpg' && $user->photo_url != 'default_avatar.jpg') {
                Storage::delete("public/fotos/{$user->photo_url}");
                $user->photo_url = 'default_avatar.jpg';
            }
        }

        if ($request->has('blocked')) {
            $user->blocked = $request->blocked;
        }
        $user->save();
        return new UserResource($user);
    }

    public function getCurrentOrder($id)
    {
        $user = User::findOrFail($id);
        if ($user->type == 'EC') {
            $query = Order::where('prepared_by', $user->id)->whereIn('status', ['H', 'P']);
        } else {
            $query = Order::where('delivered_by', $user->id)->where('status', 'T');
        }
        $order =  $query->first();
        if ($order == null) {
            return null;
        }
        $orderToSend = new stdClass();

        // ORDER
        $orderToSend->id = $order->id;
        $orderToSend->notes = $order->notes;
        $orderToSend->opened_at = $order->opened_at;
        $orderToSend->date = $order->date;
        $orderToSend->status = $order->status;
        $orderToSend->total_price = $order->total_price;
        $orderToSend->preparation_time = $order->preparation_time;
        $orderToSend->prepared_by = $order->prepared_by;
        $orderToSend->delivery_time = $order->delivery_time;
        $orderToSend->delivered_by = $order->delivered_by;
        $orderToSend->total_time = $order->total_time;
        $orderToSend->customer_id = $order->customer_id;
        $orderToSend->customer_name = "";
        $orderToSend->current_status_at = $order->current_status_at;

        // ORDER ITEMS
        $orderItems = [];
        foreach ($order->orderItems as $item) {
            // We create a new stdClass, which allow us to create a new object
            // with the attributes we want
            $itemToSend = new stdClass();

            $product = Product::find($item->product_id);
            $itemToSend->name = $product->name;
            $itemToSend->photo_url = $product->photo_url;
            $itemToSend->quantity = $item->quantity;
            $itemToSend->sub_total_price = $item->sub_total_price;

            array_push($orderItems, $itemToSend);
        }
        $orderToSend->orderItems = $orderItems;

        return new OrderResource($orderToSend);
    }


    public function usersByType(string $type_name)
    {
        return UserResource::collection(User::where('type', $type_name)->get());
    }

    public function store(StoreUserRequest $request)
    {
        $currUser = User::findOrFail(Auth::id());
        if ($currUser->can('create', $currUser)) {
            $user = new User();
            $user->fill($request->validated());
            $user->password = bcrypt($user->password);
            if ($request->hasFile('photo_url')) {
                $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
                $request->file('photo_url')->storeAs('public/fotos', $generated_new_name);
                $user->photo_url = $generated_new_name;
            } else {
                $user->photo_url = 'default_avatar.jpg';
            }
            $user->save();
            return new UserResource($user);
        }
        abort(403);
    }


    public function update(User $user, UpdateUserRequest $request)
    {
        $userOldPhoto = $user->photo_url;
        $user->fill($request->validated());

        if ($request->hasFile('photo_url')) {
            if ($userOldPhoto != 'default_avatar.jpg') {
                Storage::delete("public/fotos/{$userOldPhoto}");
            }
            $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
            $request->file('photo_url')->storeAs('public/fotos', $generated_new_name);
            $user->photo_url = $generated_new_name;
        }
        $user->save();
        return new UserResource($user);
    }


    public function destroy(User $user)
    {
        $removedUser = $user;
        if ($user->type == 'C') {
            $user->customer()->delete();
        }
        if ($user->photo_url != 'default_avatar.jpg') {
            Storage::delete("public/fotos/{$user->photo_url}");
        }
        $user->delete();
        $user->save();
        return new UserResource($removedUser);
    }

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

    public function deliverymanOrders()
    {
        return OrderResource::collection(Order::where('status', 'R')->orderBy('current_status_at', 'asc')->paginate(10));
    }

    public function changePassword($id, Request $request)
    {
        $user = User::findOrFail($id);
        $request->validate(['currentPassword' => ['required', 'string', 'min:3', new MatchOldPassword($user->password)], 'password' => ['required', 'string', 'min:3', 'confirmed']]);
        $user->password = Hash::make($request->password);
        $user->save();


        return new UserResource($user);
    }

    public function employeesIndex()
    {
        return UserToManagerResource::collection(User::whereIn('type', ['EC', 'ED'])->paginate(10));
    }
}
