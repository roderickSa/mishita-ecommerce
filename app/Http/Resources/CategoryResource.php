<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            "created_by" => [
                "name" => $this->user_creator->name,
                "email" => $this->user_creator->email,
            ],
            "updated_by" => [
                "name" => $this->user_updater->name,
                "email" => $this->user_updater->email,
            ],
            "created_at" => (new DateTime($this->created_at))->format("Y-m-d H:i:s"),
            "updated_at" => (new DateTime($this->updated_at))->format("Y-m-d H:i:s"),
        ];
    }
}
