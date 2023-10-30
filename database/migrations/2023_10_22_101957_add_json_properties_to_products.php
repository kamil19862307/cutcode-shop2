<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // TODO 3th lesson
            $table->json('json_properties')
                ->nullable();
        });
    }

    public function down(): void
    {
        if(app()->isLocal()){
            Schema::table('products', function (Blueprint $table) {
                //
            });
        }
    }
};
