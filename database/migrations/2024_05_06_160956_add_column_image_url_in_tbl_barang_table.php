<?php

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
        Schema::table('tbl_barang', function (Blueprint $table) {
            $table->string('image_url')->nullable()->default('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQxyxCTovAg5jvKFj9Bcp8UgUYr2eB48Ed2Kxrl4R37Jw&s');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_barang', function (Blueprint $table) {
            $table->dropColumn('image_url');
        });
    }
};
