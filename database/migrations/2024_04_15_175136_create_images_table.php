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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('morphable');
            $table->string('url');
            $table->unsignedBigInteger('imageable_id')->default('1');
            $table->string('imageable_type')->default('App\Models\Event_Type');
        //   //  $table->unsignedBigInteger('event_type_id');
        //    $table->foreignId('event_type_id')->references('id')->on('event_types');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropColumn('imageable_id');
            $table->dropColumn('imageable_type');
        });
    }
};
