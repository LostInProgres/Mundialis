<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_page_image', function (Blueprint $table) {
            $table->boolean('is_ref')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->boolean('is_surpressed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('page_page_image', function (Blueprint $table) {
            $table->dropColumn('is_ref');
            $table->dropColumn('is_featured');
            $table->dropColumn('is_surpressed');
        });
    }
};
