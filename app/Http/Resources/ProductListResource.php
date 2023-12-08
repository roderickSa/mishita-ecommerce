<?php

namespace App\Http\Resources;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
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
            "price" => $this->price,
            "stock" => $this->stock,
            "category" => $this->category->name,
            "images" => ProductImageResource::collection($this->product_images),
            "created_at" => (new DateTime($this->created_at))->format("Y-m-d H:i:s"),
            "updated_at" => (new DateTime($this->updated_at))->format("Y-m-d H:i:s"),
        ];
    }
}
