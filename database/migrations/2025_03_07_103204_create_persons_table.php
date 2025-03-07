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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            // ارتباط با جدول users (هر شخص متعلق به یک کاربر است)
            $table->unsignedBigInteger('user_id');
            // ارتباط با واحد سازمانی؛ ممکن است در برخی موارد اختصاص نیابد پس nullable است
            $table->unsignedBigInteger('organizational_unit_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            // فیلد جهت ذخیره سمت فرد (مثلاً مدیر، معاون، حسابدار)
            $table->string('position')->nullable();
            $table->timestamps();

            // کلید خارجی برای user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // کلید خارجی برای organizational_unit_id؛ اگر واحد حذف شود، مقدار این فیلد null شود
            $table->foreign('organizational_unit_id')->references('id')->on('organizational_units')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};