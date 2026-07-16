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
        // Create warehouses table
        if (!Schema::hasTable('warehouses')) {
            Schema::create('warehouses', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->string('address')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Create site_settings table
        if (!Schema::hasTable('site_settings')) {
            Schema::create('site_settings', function (Blueprint $table) {
                $table->id();
                $table->string('logo_url')->nullable();
                $table->string('hero_image_url')->nullable();
                $table->string('whatsapp_digits')->default('56950800542');
                $table->string('instagram_url')->default('https://www.instagram.com/motosyrepuestosspeed/');
                $table->string('phone_display')->default('+56 9 5080 0542');
                $table->string('address_line')->default('Av. Illanes 305-A, Rancagua, Chile');
                $table->string('contact_email')->default('contacto@motosyrepuestosspeed.cl');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('warehouses');
    }
};
