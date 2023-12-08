<?php

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\CustomerAddressRequest;
use App\Http\Resources\CustomerAddressResource;
use App\Http\Resources\ErrorResource;
use App\Models\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class CustomerAddressController extends Controller
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

        $customers = CustomerAddress::where("address", "LIKE", "%$search%")
            ->orderBy($sort_field, $sort_direction)
            ->paginate($per_page);

        return CustomerAddressResource::collection($customers);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): JsonResource
    {
        return CustomerAddressResource::collection($user->customer_address()->get());
    }

    public function store(CustomerAddressRequest $customerAddressRequest, User $user): JsonResource
    {
        $data = $customerAddressRequest->validated();

        $data['user_id'] = $user->id;

        if ($data["type"] === AddressType::Billing->value) {
            $customer_address_billing = CustomerAddress::where('type', '=', AddressType::Billing)->first();
            if ($customer_address_billing) {
                $error = new \Exception('already exists billing type address', Response::HTTP_BAD_REQUEST);
                return new ErrorResource($error);
            }
        }

        $customer_address = CustomerAddress::create($data);

        return new CustomerAddressResource($customer_address);
    }

    public function update(CustomerAddressRequest $customerAddressRequest, CustomerAddress $customerAddress): JsonResource
    {
        $data = $customerAddressRequest->validated();

        if ($data["type"] === AddressType::Billing->value) {
            $customer_address_billing = CustomerAddress::where('type', '=', AddressType::Billing)->where('id', '!=', $customerAddress->id)->first();
            if ($customer_address_billing) {
                $error = new \Exception('already exists billing type address', Response::HTTP_BAD_REQUEST);
                return new ErrorResource($error);
            }
        }

        $customerAddress->update($data);

        return new CustomerAddressResource($customerAddress);
    }

    public function destroy(CustomerAddress $customerAddress): Response
    {
        $customerAddress->delete();

        return response()->noContent();
    }
}
