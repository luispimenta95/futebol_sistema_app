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
        Schema::create('estadios', function (Blueprint $table) {
            $table->id();
            $table->string("nome");
            $table->text("foto");
            $table->integer("capacidade");
            $table->date('data_fundacao');
            $table->string("apellido");
            $table->foreignId("time_id")->constraint("times")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadios');
    }
};
