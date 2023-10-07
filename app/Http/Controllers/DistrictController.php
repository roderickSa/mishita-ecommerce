<?php

namespace App\Http\Controllers;

use App\Http\Requests\DistrictRequest;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $per_page = $request->per_page ?? 10;
        $search = $request->search ?? "";
        $sort_field = $request->sort_field ?? "id";
        $sort_direction = $request->sort_direction ?? "asc";

        $districts = District::where("name", "LIKE", "%$search%")
            ->orderBy($sort_field, $sort_direction)
            ->paginate($per_page);

        return DistrictResource::collection($districts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DistrictRequest $districtRequest): JsonResource
    {
        $data = $districtRequest->validated();

        $data["status"] = $data["status"] ?? 1;

        $district = District::create($data);

        return new DistrictResource($district);
    }

    /**
     * Display the specified resource.
     */
    public function show(District $district): JsonResource
    {
        return new DistrictResource($district);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DistrictRequest $districtRequest, District $district): JsonResource
    {
        $data = $districtRequest->validated();

        $district->update($data);

        return new DistrictResource($district);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district): Response
    {
        $district->delete();

        return response()->noContent();
    }
}
