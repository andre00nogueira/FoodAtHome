<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use App\Models\Customer;
use App\Models\Order;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\OrderResource;
use stdClass;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($request->has('page')) {
            return CustomerResource::collection(Customer::paginate(5));
        } else {
            return CustomerResource::collection(Customer::all());
        }
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCustomerRequest $request)
    {
        $request->validated();
        $user = new User();
        $user->name =$request->name;
        $user->email=$request->email;
        $user->password= bcrypt($request->password);
        $user->type=$request->type;
        if($request->hasFile('photo_url')){
            $generated_new_name = time() . '.' . $request->file('photo_url')->getClientOriginalExtension();
            $request->file('photo_url')->storeAs('public/fotos', $generated_new_name);
            $user->photo_url=$generated_new_name;
        }else{
            $user->photo_url='default_avatar.jpg';
        }
        $user->save();
        
        $customer = new Customer();
       
        $customer->id = $user->id;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        if($request->has('nif')){
            $customer->nif = $request->nif;
        }

        $customer->save();
        return new CustomerResource($customer);
        //return response()->json(new CustomerResource($customer,$user),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        $userData= User::findOrFail($id);
        $customerToSend =  new stdClass();
        $customerToSend->id=$customer->id;
        $customerToSend->name=$userData->name;
        $customerToSend->email=$userData->email;
        $customerToSend->address=$customer->address;
        $customerToSend->phone=$customer->phone;
        $customerToSend->nif=$customer->nif;
        $customerToSend->photo_url=$userData->photo_url;
        return new CustomerResource($customerToSend);
    }

/*

    public function show(Request $customerID)
    {
        $user = User::findOrFail($customerID);
        $customer = Customer::findOrFail($customerID);
        $aux->id = $user->id;
        $aux->name = $user->name;
        $aux->email = $user->email;
        $aux->nif = $customer->nif;
        $aux->address = $customer->address;
        $aux->phone = $customer->phone;
        $aux->photo_url = $user->photo_url;
        return new CustomerResource($customer);
    }
    */
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        $customer->save();
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function openOrders(Request $request)
    {
        return OrderResource::collection(Customer::findOrFail($request->customer)->orders()->whereNotIn('status', ["D","C"])->orderBy('created_at', 'asc')->paginate(10));
    }

    public function closedOrders(Request $request)
    {
        return OrderResource::collection(Customer::findOrFail($request->customer)->orders()->whereIn('status', ["D","C"])->orderBy('closed_at', 'desc')->paginate(10));
    } 
}
