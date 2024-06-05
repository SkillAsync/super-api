<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialtyToFreelancersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('freelancers', function (Blueprint $table) {
        $table->foreignId('category_id')->constrained("categories")->onDelete('cascade');        

});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('freelancers', function (Blueprint $table) {
            if (Schema::hasColumn('freelancers', 'category_id')){
                 $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            }
           
        });
    }
}
