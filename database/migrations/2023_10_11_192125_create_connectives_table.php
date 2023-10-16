<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class create_connective_tables extends Migration
{
    public function up(): void
    {
        Schema::create('connective_connections', function (Blueprint $table) {
            $table->id();
            $table->string('from_model_type')->index();
            $table->integer('from_model_id')->index();
            $table->string('to_model_type')->index();
            $table->integer('to_model_id')->index();
            $table->string('connection_type')->index();
            //- from datetime -
            //- through datetime (nullable)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connective_relations');
    }
}
