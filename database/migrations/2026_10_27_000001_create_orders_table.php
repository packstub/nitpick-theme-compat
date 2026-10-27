<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('customer');
            $table->string('status');
            $table->decimal('total', 10, 2);
            $table->boolean('paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }
};
