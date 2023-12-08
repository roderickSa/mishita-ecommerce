<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $per_page = $request->per_page ?? 10;
        $search = $request->search ?? "";
        $sort_field = $request->sort_field ?? "user_id";
        $sort_direction = $request->sort_direction ?? "asc";

        $customers = Customer::where("first_name", "LIKE", "%$search%")
            ->orWhere("last_name", "LIKE", "%$search%")
            ->orWhere("phone", "LIKE", "%$search%")
            ->orderBy($sort_field, $sort_direction)
            ->paginate($per_page);

        return CustomerResource::collection($customers);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResource
    {
        return new CustomerResource($user->customer()->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $customerRequest, User $user): JsonResource
    {
        $data = $customerRequest->validated();

        $data["updated_by"] = auth()->user()->id;

        $customer = Customer::find($user->id);

        if (!$customer) {

            $data["user_id"] = $user->id;

            $data["created_by"] = auth()->user()->id;

            $customer = Customer::create($data);
        } else {

            $customer->update($data);
        }

        return new CustomerResource($customer);
    }
}
