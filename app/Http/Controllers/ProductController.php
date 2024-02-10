<?php

namespace App\Http\Controllers;

use App\Helpers\ImagesManager;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductImageDestroyRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    const FULL_PRODUCTS_LIST = 'FULL_PRODUCTS_LIST';

    public function index(Request $request): JsonResource
    {
        $per_page = $request->per_page ?? 10;
        $search = $request->search ?? "";
        $sort_field = $request->sort_field ?? "id";
        $sort_direction = $request->sort_direction ?? "asc";

        $products = Product::where("title", "LIKE", "%$search%")
            ->orderBy($sort_field, $sort_direction)
            ->paginate($per_page);

        return ProductListResource::collection($products);
    }

    public function fullproducts(Request $request)
    {
        $search = $request->search ?? "";

        $search_products = Cache::remember(self::FULL_PRODUCTS_LIST, intval(Config('customvalues.REDIS_CACHE_TIME')), function () {
            return DB::table("products")->get();
        });

        $products = $search_products->filter(function ($product) use ($search) {
            if (strlen(trim($search)) > 0) {
                return str_contains($product->title, $search);
            }
            return $product;
        });

        return response()->json(['data' => $products], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateRequest $productCreateRequest, ImagesManager $imagesManager): JsonResource
    {
        $data = $productCreateRequest->validated();

        $data["created_by"] = auth()->user()->id;

        $data["updated_by"] = auth()->user()->id;

        $images = $data["images"] ?? [];

        $product = Product::create($data);

        $imagesManager->storeLocalImages($images, $product);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResource
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $productUpdateRequest, Product $product, ImagesManager $imagesManager): JsonResource
    {
        $data = $productUpdateRequest->validated();

        $data["updated_by"] = auth()->user()->id;

        $images = $data["images"] ?? [];

        $imagesManager->storeLocalImages($images, $product);

        $product->update($data);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ImagesManager $imagesManager): Response
    {
        $product_image_ids = $product->product_images()->get(['id']);

        $imagesManager->destroyLocalImages($product, $product_image_ids->toArray());

        $product->delete();

        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyImages(Product $product, ProductImageDestroyRequest $productImageDestroyRequest, ImagesManager $imagesManager): Response
    {
        $data = $productImageDestroyRequest->validated();

        $image_ids = $data["image_ids"];

        $imagesManager->destroyLocalImages($product, $image_ids);

        return response()->noContent();
    }
}
