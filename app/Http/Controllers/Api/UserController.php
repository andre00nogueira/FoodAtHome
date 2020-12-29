<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\OrderResource as OrderResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserLoggedAtRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use stdClass;

class UserController extends Controller
{
    public function me(Request $request)
    {
        return $request->user();
    }

    public function index(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5));
        } else {
            return UserResource::collection(User::all());
        }
        /*Caso não se pretenda fazer uso de Eloquent API Resources (https://laravel.com/docs/5.5/eloquent-resources), é possível implementar com esta abordagem:
        if ($request->has('page')) {
            return User::with('department')->paginate(5);;
        } else {
            return User::with('department')->get();;
        }*/
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
        $order =  Order::where('prepared_by', '=', $id)->where('status', '=', 'H')->orWhere('status', '=', 'P')->first();
        if ($order == null) {
            return null;
        }
        return $order;
    }


    public function store(StoreUserRequest $request)
    {
        $user = new User();
        $user->fill($request->validated());
        $user->password = bcrypt($user->password);
        $user->save();
        return response()->json(new UserResource($user), 201);
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
        $user->delete();
        return response()->json(null, 204);
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

    public function deliverymanOrders()
    {
        return OrderResource::collection(Order::where('status', 'R')->orderBy('current_status_at', 'desc')->paginate(10));
    }

    public function deliverymanCurrentOrder($id)
    {
        $order =  Order::where('delivered_by', '=', $id)->whereIn('status', ['R','T'])->first();
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
}
