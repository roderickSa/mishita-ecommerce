<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "published" => $this->published,
            "description" => $this->description,
            "price" => $this->price,
            "stock" => $this->stock,
            "category" => [
                "name" => $this->category->name,
                "slug" => $this->category->slug,
            ],
            "images" => $this->product_images,
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
