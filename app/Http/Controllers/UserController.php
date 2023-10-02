<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $per_page = $request->per_page ?? 10;
        $search = $request->search ?? "";
        $sort_field = $request->sort_field ?? "id";
        $sort_direction = $request->sort_direction ?? "asc";

        $users = User::where("name", "LIKE", "%$search%")
            ->orWhere("email", "LIKE", "%$search%")
            ->orderBy($sort_field, $sort_direction)
            ->paginate($per_page);

        return UserResource::collection($users);
    }

    public function show(User $user): JsonResource
    {
        return new UserResource($user);
    }
}
