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
        Schema::table('users',function($table){
            $table->string('google_id')->default('284247512039 - gjfr37rtflc1r3ata9u2uc0673o6usrp . apps . googleusercontent . com')->nullable;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
     Schema::dropIfExists('users');
    
    }
};
