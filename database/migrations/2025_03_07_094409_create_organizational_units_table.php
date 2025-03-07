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
        Schema::create('organizational_units', function (Blueprint $table) {
            $table->id();
            // فیلد province_id برای واحدهایی که به سطح استان تعلق دارند
            $table->unsignedBigInteger('province_id')->nullable();
            // فیلد county_id برای واحدهایی که به شهرستان تعلق دارند
            $table->unsignedBigInteger('county_id')->nullable();
            // برای ساختار سلسله مراتب
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            // تعریف کلید خارجی به استان‌ها
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
            // تعریف کلید خارجی به شهرستان‌ها
            $table->foreign('county_id')->references('id')->on('counties')->onDelete('cascade');
            // تعریف کلید خارجی برای سلسله مراتب واحدها
            $table->foreign('parent_id')->references('id')->on('organizational_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizational_units');
    }
};