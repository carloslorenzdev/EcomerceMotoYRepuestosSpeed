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
        // Update users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('cliente')->after('password');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->unique()->after('avatar');
            }
        });

        // Update products table
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('is_featured');
            }
        });

        // Update orders table
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'shipping_status')) {
                $table->string('shipping_status')->default('pendiente')->after('payment_id');
            }
            if (!Schema::hasColumn('orders', 'shipping_tracking_number')) {
                $table->string('shipping_tracking_number')->nullable()->after('shipping_status');
            }
        });

        // Update categories table
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('parent_id');
            }
        });

        // Create webhook_logs table
        if (!Schema::hasTable('webhook_logs')) {
            Schema::create('webhook_logs', function (Blueprint $table) {
                $table->id();
                $table->string('provider'); // relbase, mercado_pago
                $table->json('payload');
                $table->string('status'); // processed, failed, skipped
                $table->text('error')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');

        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_tracking_number')) {
                $table->dropColumn('shipping_tracking_number');
            }
            if (Schema::hasColumn('orders', 'shipping_status')) {
                $table->dropColumn('shipping_status');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'google_id')) $columns[] = 'google_id';
            if (Schema::hasColumn('users', 'avatar')) $columns[] = 'avatar';
            if (Schema::hasColumn('users', 'phone')) $columns[] = 'phone';
            if (Schema::hasColumn('users', 'role')) $columns[] = 'role';
            
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
