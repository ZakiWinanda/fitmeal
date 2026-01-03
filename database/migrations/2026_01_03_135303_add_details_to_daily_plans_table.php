<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('daily_plans', function (Blueprint $table) {
        $table->text('instructions')->nullable(); // Untuk cara buat resep / cara olahraga
        $table->string('image_url')->nullable();  // Opsional: untuk foto makanan/olahraga
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_plans', function (Blueprint $table) {
            //
        });
    }
};
