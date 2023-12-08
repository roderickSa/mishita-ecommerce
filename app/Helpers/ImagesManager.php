<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;

class ImagesManager
{

    private string $PATH_PRODUCT_IMAGES_FOLDER = "images/products";

    public function storeLocalImages(array $images, Product $product)
    {
        $product_images = [];

        if (!Storage::exists($this->PATH_PRODUCT_IMAGES_FOLDER)) {
            Storage::makeDirectory($this->PATH_PRODUCT_IMAGES_FOLDER, 0777, true);
        }

        foreach ($images as $key => $image) {
            $extension = $image->getClientOriginalExtension();

            $image_name = Str::random() . "." . $extension;

            $complete_image_url = $this->PATH_PRODUCT_IMAGES_FOLDER . "/" . $image_name;

            $resize_image = Image::make($image)->resize(768, 450, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($extension);

            Storage::put($complete_image_url, $resize_image);

            $url_image = Storage::url($complete_image_url);

            $product_images[] = ProductImage::create([
                "product_id" => $product->id,
                "name" => $image_name,
                "url" => $url_image,
            ]);
        }

        return $product_images;
    }

    public function destroyLocalImages(Product $product, array $image_ids)
    {
        if (count($image_ids) == 0) return;

        $product_images = ProductImage::whereIn("id", $image_ids)
        ->where("product_id", $product->id)
        ->get();

        foreach ($product_images as $key => $product_image) {
            $complete_image_url = $this->PATH_PRODUCT_IMAGES_FOLDER . "/" . $product_image->name;

            if (Storage::exists($complete_image_url)) {
                Storage::delete($complete_image_url);
            }

            $product_image->delete();
        }
    }
}
