<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Tabel Users
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Ditambahkan agar tidak error saat seeder/update
            $table->string('password')->nullable();
            $table->string('google_id')->nullable();
            $table->string('role')->default('user');
            $table->boolean('is_subscribed')->default(false);
            $table->date('subscription_end_date')->nullable();
            $table->json('profile_data')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Tabel Daily Plans (Menu & Olahraga - Fitur Premium)
        Schema::create('daily_plans', function (Blueprint $table) {
            $table->id();
            $table->date('plan_date');
            $table->string('type');
            $table->string('category')->nullable(); // Untuk target BMI
            $table->string('title');
            $table->text('description')->nullable(); 
            $table->integer('calories')->default(0);
            $table->text('instructions')->nullable(); // Untuk instruksi memasak/latihan
            $table->timestamps();
        });

        // Tabel Transaksi
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('order_id')->unique();
            $table->integer('amount');
            $table->string('status')->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });

        // Tabel Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // Tabel Cache
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        // Tabel Visitor Logs
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->integer('count')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
        Schema::dropIfExists('daily_plans');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('visitor_logs');
    }
};
