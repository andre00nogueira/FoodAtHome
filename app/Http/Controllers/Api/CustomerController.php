<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\User;
use App\Models\Customer;
use App\Http\Resources\CustomerResource;

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
        if($request->has('photo_url')){
            /*
            $upload_path = public_path('foto');
            $file_name = $request->photo_url->getClientOriginalName();
            $generated_new_name = time() . '.' . $request->photo_url->getClientOriginalExtension();
            $request->file->move($upload_path, $generated_new_name);
            $user->photo_url=$file_name;*/
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
        return response()->json(new CustomerResource($customer,$user),201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show($id)
    {
        return new CustomerResource(Customer::findOrFail($id));
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
}
