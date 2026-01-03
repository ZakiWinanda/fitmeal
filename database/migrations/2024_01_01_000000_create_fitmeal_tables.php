<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('role')->default('user');
            $table->boolean('is_subscribed')->default(false);
            $table->date('subscription_end_date')->nullable();
            $table->json('profile_data')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('daily_plans', function (Blueprint $table) {
            $table->id();
            $table->date('plan_date');
            $table->string('type');
            $table->string('title');
            $table->text('description');
            $table->integer('calories')->default(0);
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('order_id')->unique();
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('daily_plans');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
    }
};
