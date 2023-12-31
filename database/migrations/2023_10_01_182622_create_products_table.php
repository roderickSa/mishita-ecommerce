<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200)->unique()->index();
            $table->string('slug', 200)->unique();
            $table->boolean('published')->default(false);
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer("stock")->default(0);
            $table->foreignId("category_id")->constrained();
            $table->foreignIdFor(User::class, "created_by");
            $table->foreignIdFor(User::class, "updated_by");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
