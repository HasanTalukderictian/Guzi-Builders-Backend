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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique();
            $table->string('employee_name');
            $table->enum('status', ['Active', 'Deactive'])->default('Active');
            $table->date('joining_date');
            $table->string('designation');
            $table->string('department');
            $table->string('contact_no', 15);
            $table->string('image')->nullable()->change();// Store image file names as JSON
            $table->timestamps();
        });
    }




    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }



};
